<?php
/**
 * Created by PhpStorm.
 * User: malal
 * Date: 11/8/2018
 * Time: 11:18 PM
 */

$title = "إعدادات الحساب";

require 'templates/header.php';
require_once 'models/User.php';
require_once 'models/Admin.php';
require_once 'models/Seller.php';
require_once 'utils/files_utils.php';
require_once 'utils/security_utils.php';

if (!isset($_SESSION['id'])) {
    header("Location:/");
    die();
}

if (isset($_POST['register'])) {


    $message = '';
    $valid = true;
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
    } else {
        $valid = false;
        $message .= 'يرجى إدخال الاسم';
        $class = 'error-message';
    }

    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = $_POST['email'];

        if ($_SESSION['user_type'] == USER_TYPE_USER) {
            $user = User::byEmail($email);
            $seller = Seller::byEmail($email);
            $admin = Admin::byEmail($email);
            if ($user != null && $user->id != $_SESSION['id'] || $seller != null || $admin != null) {
                $valid = false;
                if ($message != '') $message .= '<br>';
                $message .= 'البريد الإلكتروني مسجل مسبقا';
                $class = 'error-message';
            }
        } elseif ($_SESSION['user_type'] == USER_TYPE_SELLER) {
            $seller = Seller::byEmail($email);
            $user = User::byEmail($email);
            $admin = Admin::byEmail($email);
            if ($seller != null && $seller->id != $_SESSION['id'] || $user != null || $admin != null) {
                $valid = false;
                if ($message != '') $message .= '<br>';
                $message .= 'البريد الإلكتروني مسجل مسبقا';
                $class = 'error-message';
            }
        } else {
            $seller = Seller::byEmail($email);
            $user = User::byEmail($email);
            $admin = Admin::byEmail($email);
            if ($admin != null && $admin->id != $_SESSION['id'] || $user != null || $seller != null) {
                $valid = false;
                if ($message != '') $message .= '<br>';
                $message .= 'البريد الإلكتروني مسجل مسبقا';
                $class = 'error-message';
            }
        }
    } else {
        $message = 'يرجى إدخال بريد إلكتروني صحيح';
        $class = 'error-message';
        $valid = false;
    }


    if (isset($_POST['password']) && strlen($_POST['password']) > 0) {
        if (strlen($_POST['password']) >= 6) {
            $password = $_POST['password'];
        } else {
            $valid = false;
            if ($message != '') $message .= '<br>';
            $message .= 'كلمة المرور قصير جداً';
            $class = 'error-message';
        }
    }

    if (isset($_POST['location'])) {
        if (strlen($_POST['location']) == 0) {
            $valid = false;
            if ($message != '') $message .= '<br>';
            $message .= 'العنوان فارغ';
            $class = 'error-message';
        }
    }

    if ($valid) {
        switch ($_SESSION['user_type']) {
            case USER_TYPE_USER:

                $user = User::byId($_SESSION['id']);
                $user->setEmail($email);
                $user->setName($name);
                $user->update();
                if (isset($password)) {
                    $user->salt = uniqid("", true);
                    $user->password = hashSh3($password, $user->salt);
                    $user->updatePassword();
                }
                break;

            case USER_TYPE_SELLER:


                $user = Seller::byId($_SESSION['id']);
                $user->setEmail($email);
                $user->setName($name);
                $user->setLocation($_POST['location']);
                $user->update();
                if (isset($password)) {
                    $user->salt = uniqid("", true);
                    $user->password = hashSh3($password, $user->salt);
                    $user->updatePassword();
                }
                break;

            case USER_TYPE_ADMIN:

                $user = Admin::byId($_SESSION['id']);
                $user->setEmail($email);
                $user->setName($name);
                $user->update();
                if (isset($password)) {
                    $user->salt = uniqid("", true);
                    $user->password = hashSh3($password, $user->salt);
                    $user->updatePassword();
                }
                break;
        }

        $message = "تم تعديل البيانات بنجاح.";
        $class = 'success-message';
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
    }
}


?>


    <div id="register-container">
        <h3>إعدادات الحساب</h3>
        <form method="post">


            <?php

            if (isset($message)) {
                echo "
                        <div class='admin-message $class'>
                        $message
                        </div>
                        ";
            }

            ?>


            <div class="input-group">
                <label for="email-input">البريد الإلكتروني</label>
                <input name="email" required id="email-input" class="input" type="email"
                       placeholder="email@example.com" value="<?php
                if (isset($_POST['email']))
                    echo $_POST['email'];
                else
                    echo $_SESSION['email'] ?>"/>
            </div>

            <div class="input-group">
                <label for="name-input">الاسم</label>
                <input name="name" required id="name-input" class="input" type="text"
                       value="<?php
                       if (isset($_POST['name']))
                           echo $_POST['name'];
                       else
                           echo $_SESSION['name']
                       ?>"
                       placeholder="الاسم الظاهر للجميع"/>
            </div>

            <?php
            if ($_SESSION['user_type'] == USER_TYPE_SELLER) {
                if ($_POST['location'])
                    $location = Seller::byId($_SESSION['id'])->location;
                echo "
                <div class='input-group'>
                    <label for='name-input'>عنوان المحل</label>
                    <input id='name-input' value='" . (isset($_POST['location']) ? $_POST['location'] : $location) . "' name='location' class='input' type='text' placeholder='عنوان المحل' required/>
                </div>
                ";
            }

            ?>

            <div class="input-group">
                <label for="password-input">كلمة المرور</label>
                <input name="password" type="password" class="input" placeholder="******" id="password-input"/>
                <div class="input-hint text-center">اتركها فارغة إذا كنت لا تريد تغييرها</div>
            </div>

            <div class="clear"></div>

            <input type="submit" name="register" value="حفظ" class="primary-colored-button-rounded register-button"/><br>
        </form>
    </div>


<?php
require 'templates/footer.php';
