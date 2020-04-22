<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 29/10/18
 * Time: 09:00 ص
 */


require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Category.php";


$edit = isset($_GET['edit']);
if ($edit) {
    $title = "تعديل تصنيف";

    $catId = $_GET['edit'];
    $category = Category::byId($catId);
    if ($category == null) {
        $edit = false;
        $message = "لم يتم العثور على التصنيف!";
        $class = 'error-message';
    }
} else {
    $title = "إضافة تصنيف";
}

require_once 'header.php';

echo "    <div class=\"display-flex\">";

require 'menu.php';

echo "        <div id=\"admin-panel-content\" class='flex-column'>";


if (isset($_POST['action'])) {
    $edit = $_POST['action'] == 'edit';

    $name = $_POST['name'];
    $parent = $_POST['parent'];

    if ($edit) {
        $id = $_POST['id'];
        $category = Category::byId($id);
        if ($category === NULL) {
            $edit = false;
            $message = "لم يتم العثور على التصنيف!";
            $class = 'error-message';
        } else {
            $category->setName($name);
            $category->setParentId($parent);
            $res = $category->update();
            if ($res) {
                $message = "تم تعديل التصنيف $category->name بنجاح!";
                $class = 'success-message';
            }
        }
    } else {
        $category = new Category($name, $parent);
        $res = $category->create();

        if ($res) {
            $message = "تم إضافة التصنيف $category->name بنجاح!";
            $class = 'success-message';
        }

    }
}


if (isset($message))
    echo "
                        <div class='admin-message $class'>
                        $message
                        </div>
                        ";

echo "
            <div class=\"orange-block-item admin-form-container\">";

if ($edit) {
    echo "<h4>تعديل تصنيف</h4>";
} else {
    echo "<h4>إضافة تصنيف</h4>";
}

echo "                <form method='post'>
                    <div class=\"input-group\">
                        <label for=\"category-name\">اسم التصنيف</label>
                        <input name='name' type=\"text\" value='" . ($edit ? $category->name : "") . "' id=\"category-name\" class=\"input\"/>
                    </div>

                    <div class=\"input-group\">
                        <label for=\"category-parent\">التصنيف الأب:</label>
                        <select name='parent'>
                            <option value='0'>-</option>";

foreach (Category::all(true) as $cat) {
    echo '<option ' . ($edit && $category->parentId == $cat->id ? 'selected' : '') . ' value="' . $cat->id . '">' . $cat->name . '</option>';
}

echo "
                        </select>
                    </div>";

if ($edit) {
    echo "<input type='hidden' name='id' value='$category->id' />";
    echo "<input type='hidden' name='action' value='edit' />";
} else {
    echo "<input type='hidden' name='action' value='create' />";
}

echo "
                    <input type=\"submit\" value=\"حفظ\" class=\"primary-colored-button btn-save\"/>
                </form>

            </div>
        </div>
        <div class=\"clear\"></div>
    </div>";

require '../templates/footer.php';


