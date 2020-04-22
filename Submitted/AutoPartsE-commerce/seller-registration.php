<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 28/10/18
 * Time: 09:26 م
 */
$title = 'التسجيل كـ بائع';

require 'templates/header.php';
require_once 'models/Seller.php';
require_once 'utils/files_utils.php';
require_once 'utils/security_utils.php';

if (isset($_POST['register'])) {


    $message = '';
    $valid = true;
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
    } else {
        $valid = false;
        $message .= 'يرجى إدخال اسم المحل';
        $class = 'error-message';
    }

    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = $_POST['email'];
        if (Seller::byEmail($email) != null || Seller::byEmail($email) != null) {
            $valid = false;
            if ($message != '') $message .= '<br>';
            $message .= 'البريد الإلكتروني مسجل مسبقا';
            $class = 'error-message';
        }
    } else {
        $message = 'يرجى إدخال بريد إلكتروني صحيح';
        $class = 'error-message';
        $valid = false;
    }

    if (isset($_POST['location'])) {
        $location = $_POST['location'];
    } else {
        $valid = false;
        if ($message != '') $message .= '<br>';
        $message .= 'يرجى إدخال عنوان المحل';
        $class = 'error-message';
    }

    if (isset($_POST['password']) && strlen($_POST['password']) >= 6) {
        $password = $_POST['password'];
    } else {
        $valid = false;
        if ($message != '') $message .= '<br>';
        $message .= 'كلمة المرور قصير جداً';
        $class = 'error-message';
    }

    if ($valid)
        if (isFileSet($_FILES['certificate'])) {
            $file = $_FILES['certificate'];
            $result = uploadImage($file, $_SERVER['DOCUMENT_ROOT'] . '/files/certificates');
            $valid = false;
            switch ($result) {
                case $ERR_EXT:
                    if ($message != '') $message .= '<br>';
                    $message .= 'صيغة الملف غير مدعومة';
                    $class = 'error-message';
                    break;
                case $ERR_UNKNOWN:
                    if ($message != '') $message .= '<br>';
                    $message .= 'حصل خطأ أثناء رفع الملف';
                    $class = 'error-message';
                    break;
                case $ERR_SIZE:
                    if ($message != '') $message .= '<br>';
                    $message .= 'حجم الملف يتجاوز الحجم المسموح به (5MB)';
                    $class = 'error-message';
                    break;
                default:
                    $path = $result;
                    $valid = true;
                    break;
            }
        } else {
            $valid = false;
            if ($message != '') $message .= '<br>';
            $message .= 'يرجى رفع صورة السجل التجاري';
            $class = 'error-message';
        }

    if ($valid) {
        $seller = new Seller($name, $email, $location, $path);
        $seller->setStatus(Seller::$WAITING);
        $salt = uniqid('', true);
        $seller->setSalt($salt);
        $password = hashSh3($password, $salt);
        $seller->setPassword($password);
        if ($seller->create()) {
            $message = "تم تسجيل طلبك بنجاح، سيتم مراجعته قريباً.";
            $class = 'success-message';
        } else {
            $message = "حصل خطأ أثناء التسجيل";
            $class = 'error-message';
        }
    }
}


?>


    <div id='register-container'>

        <h3>التسجيل كـ بائع</h3>

        <?php

        if (isset($message)) {
            echo "
                        <div class='admin-message $class'>
                        $message
                        </div>
                        ";
        }

        ?>


        <form method="post" enctype="multipart/form-data">
            <div class='input-group'>
                <label for='email-input'>البريد الإلكتروني</label>
                <input id='email-input' name='email' class='input' type='email' placeholder='email@example.com'
                       required/>
            </div>

            <div class='input-group'>
                <label for='name-input'>اسم المحل</label>
                <input id='name-input' name='name' class='input' type='text' placeholder='اسم المحل' required/>
            </div>

            <div class='input-group'>
                <label for='name-input'>عنوان المحل</label>
                <input id='name-input' name='location' class='input' type='text' placeholder='عنوان المحل' required/>
            </div>


            <div class='input-group'>
                <label for='password-input'>كلمة المرور</label>
                <input type='password' name='password' class='input' placeholder='******' required id='password-input'/>
            </div>


            <div class='input-group file-upload-container'>
                <label for='certificate-upload'>صورة للسجل التجاري</label>
                <button type='button' class='light-orange-highlighted-button'>تحميل الملف</button>
                <input type='file' required name='certificate' id='certificate-upload'/>
            </div>

            <div class='clear'></div>

            <input type='submit' name='register' value='تسجيل' class='primary-colored-button-rounded register-button'/>
            <p>لديك حساب؟<a href='/login.php'>سجل دخولك من هنا</a></p>
        </form>
    </div>


<?php
require 'templates/footer.php';

