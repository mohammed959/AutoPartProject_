<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 27/10/18
 * Time: 10:45 م
 */


$title = "تسجيل الدخول";

require 'templates/header.php';
require_once 'models/User.php';
require_once 'models/Seller.php';
require_once 'models/Admin.php';
require_once 'utils/constants.php';
require_once 'utils/security_utils.php';

if (isset($_POST['login'])) {
    login();
}

define("USER", 0);
define("SELLER", 1);
define("ADMIN", 2);

function login()
{
    $email = $_POST['email'];
    $user = User::byEmail($email);

    if ($user != null) {
        performLogin(USER_TYPE_USER, $user);
        return;
    }
    $seller = Seller::byEmail($email);
    if ($seller != null) {
        performLogin(USER_TYPE_SELLER, $seller);
        return;
    }
    $admin = Admin::byEmail($email);
    if ($admin != null) {
        performLogin(USER_TYPE_ADMIN, $admin);
        return;
    }

    global $message;
    $message = "<div class='error-message'>خطأ بمعلومات الدخول</div>";

}

function performLogin($userType, $userData)
{
    $password = hashSh3($_POST['password'], $userData->salt);


    switch ($userType) {
        case USER_TYPE_USER:
            if ($userData->status == User::$BLOCKED) {
                global $message;
                $message = "<div class='error-message'>الحساب المدخل محظور</div>";
                return;
            } else if ($userData->status == User::$WAITING) {
                global $message;
                $message = "<div class='warning-message'>يرجى تفعيل الحساب</div>";
                return;

            }
            break;
        case USER_TYPE_SELLER:
            if ($userData->status == Seller::$BLOCKED) {
                global $message;
                $message = "<div class='error-message'>الحساب المدخل محظور</div>";
                return;
            }
            break;
    }

    if ($userData->password != $password) {
        global $message;
        $message = "<div class='error-message'>خطأ بمعلومات الدخول</div>";
        return;
    }
    session_start();

    $_SESSION['id'] = $userData->id;
    $_SESSION['email'] = $userData->email;
    $_SESSION['name'] = $userData->name;
    $_SESSION['user_type'] = $userType;

    header("Location: /");
}

?>


    <div id="login-container">
        <h3>تسجيل الدخول</h3>

        <?php
        if (isset($message)) {
            echo $message;
        }

        ?>

        <form method="post">

            <div class="input-group">

                <label for="email-input">البريد الإلكتروني</label>
                <input name="email" id="email-input" class="input" required type="email"
                       placeholder="email@example.com"/>
            </div>

            <br>

            <div class="input-group">
                <label for="password-input">كلمة المرور</label>
                <input name="password" type="password" class="input" required placeholder="******" id="password-input"/>
            </div>

            <div class="clear"></div>

            <input name="login" type="submit" value="تسجيل الدخول" class="primary-colored-button-rounded login-button"/>

            <p>ليس لديك حساب؟ <a href="#">سجل هنا</a></p>
        </form>
    </div>


<?php
require 'templates/footer.php';
