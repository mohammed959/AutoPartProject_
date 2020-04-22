<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 27/10/18
 * Time: 11:42 م
 */

$title = "التسجيل";

require 'templates/header.php';
require_once 'models/User.php';
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
        $message .= 'يرجى إدخال الاسم';
        $class = 'error-message';
    }

    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = $_POST['email'];
        if (User::byEmail($email) != null || Seller::byEmail($email)) {
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


    if (isset($_POST['password']) && strlen($_POST['password']) >= 6) {
        $password = $_POST['password'];
    } else {
        $valid = false;
        if ($message != '') $message .= '<br>';
        $message .= 'كلمة المرور قصير جداً';
        $class = 'error-message';
    }


    if ($valid) {
        $user = new User($name, $email);

        $user->setStatus(User::$WAITING);


        $message = "الرجاء الضغط على الرابط التالي لتفعيل حسابك:";
        $message .= "<br>";
        $message .= "<a href='localhost/activate.php?token=";
        $token = uniqid('', true);
        $message .= $token . "'>تفعيل</a>";

        $headers = "From: autoparts.webtest@gmail.com" . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        mail($email, "تفعيل حسابك في موقع قطع غيار السيارات", $message, $headers);

        $user->token = $token;
        $salt = uniqid('', true);
        $user->setSalt($salt);
        $user->setPassword(hashSh3($password, $salt));
        if ($user->create()) {
            $message = "تم إرسال رسالة تفعيل إلى بريدك الإلكتروني.";
            $class = 'success-message';
        } else {
            $message = "حصل خطأ أثناء التسجيل";
            $class = 'error-message';
        }
    }
}


?>


    <div id="register-container">
        <h3>التسجيل</h3>
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
                       placeholder="email@example.com"/>
            </div>

            <div class="input-group">
                <label for="name-input">الاسم</label>
                <input name="name" required id="name-input" class="input" type="text"
                       placeholder="الاسم الظاهر للجميع"/>
            </div>

            <div class="input-group">
                <label for="password-input">كلمة المرور</label>
                <input name="password" required type="password" class="input" placeholder="******" id="password-input"/>
            </div>

            <div class="clear"></div>

            <input type="submit" name="register" value="تسجيل" class="primary-colored-button-rounded register-button"/>
            <a href="/seller-registration.php" id="seller-registration" class="primary-highlighted-button">التسجيل كـ
                بائع</a>

            <p>لديك حساب؟<a href="/login.php">سجل دخولك من هنا</a></p>
        </form>
    </div>


<?php
require 'templates/footer.php';
