<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 01/11/18
 * Time: 07:46 م
 */


require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Brand.php";


$edit = isset($_GET['edit']);
if ($edit) {
    $title = "تعديل سيارة";

    $brandId = $_GET['edit'];
    $brand = Brand::byId($brandId);
    if ($brand == null) {
        $edit = false;
        $message = "لم يتم العثور على السيارة!";
        $class = 'error-message';
    }
} else {
    $title = "إضافة سيارة";
}

require_once 'header.php';

echo "    <div class=\"display-flex\">";

require 'menu.php';

echo "        <div id=\"admin-panel-content\" class='flex-column'>";


if (isset($_POST['action'])) {
    $edit = $_POST['action'] == 'edit';

    $name = $_POST['name'];
    $startModel = $_POST['startModel'];
    $endModel = $_POST['endModel'];
    if ($endModel == '-' || $endModel == '"-"')
        unset($endModel);
    $manuId = $_POST['manuId'];

    if ($edit) {
        $id = $_POST['id'];
        $brand = Brand::byId($id);
        if ($brand === NULL) {
            $edit = false;
            $message = "لم يتم العثور على السيارة!";
            $class = 'error-message';
        } else {
            $brand->setName($name);
            $brand->setStartModel($startModel);
            $brand->setEndModel($endModel);
            $res = $brand->update();
            if ($res) {
                $message = "تم تعديل السيارة $brand->name بنجاح!";
                $class = 'success-message';
            }
        }
    } else {
        $brand = new Brand($name, $startModel, $endModel, $manuId);
        $res = $brand->create();

        if ($res) {
            $message = "تم إضافة السيارة $brand->name بنجاح!";
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
            <div class=\"orange-block-item admin-form-container\">
                <h4>$title</h4>
                <form method='post'>
                    <div class=\"input-group\">
                        <label for=\"brand-name\">اسم السيارة</label>
                        <input name='name' type=\"text\" value='" . ($edit ? $brand->name : "") . "' id=\"brand-name\" class=\"input\"/>
                    </div>

                    <div class=\"input-group\">
                        <label for=\"startModel\">أول موديل</label>
                        <input name='startModel' type=\"text\" value='" . ($edit ? $brand->startModel : "") . "' id=\"startModel\" class=\"input\"/>
                    </div>
                    <div class=\"input-group\">
                        <label for=\"endModel\">آخر موديل</label>
                        <input name='endModel' type=\"text\" value='";

if ($edit) {
    if (isset($brand) && isset($brand->endModel))
        echo $brand->endModel;
    else
        echo '-';
} else
    echo '-';

echo "' id=\"endModel\" class=\"input\"/>
                    </div>

                    <div class=\"input-group\">
                        <label for=\"brand-parent\">الشركة المصنعة:</label>
                        <select name='manuId'>";

foreach (Manufacturer::all(1, Manufacturer::nOfManus()) as $manu) {
    echo '<option ' . ($edit && $brand->manufacturerId == $manu->id ? 'selected' : '') . ' value="' . $manu->id . '">' . $manu->name . '</option>';
}

echo "
                        </select>
                    </div>";

if ($edit) {
    echo "<input type='hidden' name='id' value='$brand->id' />";
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


