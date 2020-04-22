<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 01/11/18
 * Time: 11:24 م
 */


require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Manufacturer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/utils/files_utils.php";


$edit = isset($_GET['edit']);
if ($edit) {
    $title = "تعديل شركة";

    $manuId = $_GET['edit'];
    $manu = Manufacturer::byId($manuId);
    if ($manu == null) {
        $edit = false;
        $message = "لم يتم العثور على الشركة!";
        $class = 'error-message';
    }
} else {
    $title = "إضافة شركة";
}

require_once 'header.php';

echo "    <div class=\"display-flex\">";

require 'menu.php';

echo "        <div id=\"admin-panel-content\" class='flex-column'>";


if (isset($_POST['action'])) {
    $edit = $_POST['action'] == 'edit';

    $name = $_POST['name'];
    $fileOk = true;
    if (isFileSet($_FILES['logo'])) {
        $result = uploadImage($_FILES['logo'], $_SERVER['DOCUMENT_ROOT'] . '/files/logos');
        switch ($result) {
            case $ERR_EXT:
                $fileOk = false;
                $message = 'صيغة الملف غير مدعومة';
                $class = 'error-message';
                break;
            case $ERR_UNKNOWN:
                $fileOk = false;
                $message = 'حصل خطأ أثناء رفع الملف';
                $class = 'error-message';
                break;
            case $ERR_SIZE:
                $fileOk = false;
                $message = 'حجم الملف يتجاوز الحجم المسموح به (5MB)';
                $class = 'error-message';

                break;
            default:
                $path = $result;
                break;
        }
    }

    if ($fileOk)
        if ($edit) {
            $id = $_POST['id'];
            $manu = Manufacturer::byId($id);
            if ($manu === NULL) {
                $edit = false;
                $message = "لم يتم العثور على الشركة!";
                $class = 'error-message';
            } else {
                $manu->setName($name);
                if (isset($path))
                    $manu->setLogoUrl($path);
                $res = $manu->update();
                if ($res) {
                    $message = "تم تعديل الشركة $manu->name بنجاح!";
                    $class = 'success-message';
                }
            }
        } else {
            $manu = new Manufacturer($name);
            if (isset($path)) $manu->setLogoUrl($path);
            $res = $manu->create();

            if ($res) {
                $message = "تم إضافة الشركة $manu->name بنجاح!";
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
    echo "<h4>تعديل شركة</h4>";
} else {
    echo "<h4>إضافة شركة</h4>";
}

echo "         <form method='post' enctype='multipart/form-data'>
                    <div class=\"input-group\">
                        <label for=\"manu-name\">اسم الشركة</label>
                        <input name='name' type=\"text\" value='" . ($edit ? $manu->name : "") . "' id=\"manu-name\" class=\"input\"/>
                    </div>

                    <div class=\"input-group\">
                        <label for=\"manu-parent\">الشعار</label>
                        <input type='file' name='logo' />
                    </div>";

if ($edit) {
    echo "<input type='hidden' name='id' value='$manu->id' />";
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


