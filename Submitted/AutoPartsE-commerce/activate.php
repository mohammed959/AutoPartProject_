<?php
/**
 * Created by PhpStorm.
 * User: malal
 * Date: 12/4/2018
 * Time: 11:59 AM
 */

$title = "تفعيل حساب المستخدم";

require_once 'templates/header.php';

if (!isset($_GET['token']))
    header("Location:/");


$token = $_GET['token'];

require_once 'models/User.php';

echo "<div class='container' style='width: 250px;'>";


$user = User::byToken($token);

if ($user == null) {
    echo "<div class='admin-message error-message'>الرمز غير صحيح</div>";
} else {
    if ($user->status == User::$ACTIVE) {
        echo "<div class='admin-message warning-message'>حسابك مفعل بالفعل!</div>";
    } else {
        $user->setStatus(User::$ACTIVE);
        $user->update();
        echo "<div class='admin-message success-message'>تم تفعيل حسابك بنجاح</div>";
    }
}


echo "</div>";

require_once 'templates/footer.php';