<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <link href="https://fonts.googleapis.com/css?family=Changa|Lateef&amp;subset=arabic" rel="stylesheet">-->
<!--    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/droid-arabic-kufi" type="text/css"/>-->
<!--    <link rel="stylesheet" type="text/css" href="/css/style.css"/>-->
<!--    <link rel="stylesheet" type="text/css" href="/admin-panel/style.css"/>-->
<!--    <link rel="stylesheet" type="text/css" href="/css/font-awesome/css/all.css"/>-->
<!---->
<!--    <script type="text/javascript" src="/js/js.js"></script>-->
<!---->
<!---->
<!--    <title>--><?php //echo $title ?><!--</title>-->
<!--</head>-->
<!--<body dir="rtl">-->
<!---->
<!--<header id="header-container">-->
<!--    <div class="container">-->
<!---->
<!--        <a href="/" id="logo">اسم الموقع</a>-->
<!---->
<!--        <div id="search-container">-->
<!--            <div class="space"></div>-->
<!--            <form method="get">-->
<!--                <input type="search" id="main-search-input" placeholder="عن ماذا تبحث؟"/>-->
<!--                <button type="submit" class="search-submit"><i class="far fa-search"></i></button>-->
<!--            </form>-->
<!--            <div class="space"></div>-->
<!--        </div>-->
<!---->
<!--        <a href="/login.php" id="signin-button" class="primary-colored-button">تسجيل الدخول</a>-->
<!--        <a href="/register.php" id="signup-button" class="primary-highlighted-button">تسجيل</a>-->
<!--        <i class="far fa-shopping-cart fa-lg" id="shopping-cart"></i>-->
<!---->
<!--    </div>-->
<!---->
<!--    <div class="clear"></div>-->
<!--</header>-->
<!---->
<!--<div class="header-margin"></div>-->
<!---->


<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 27/10/18
 * Time: 01:40 م
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/constants.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Changa|Lateef&amp;subset=arabic" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/droid-arabic-kufi" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/admin-panel/style.css"/>
    <link rel="stylesheet" type="text/css" href="/css/font-awesome/css/all.css"/>

    <title><?php echo $title ?></title>

    <script type="text/javascript" src="/js/js.js"></script>
</head>
<body dir="rtl">

<header id="header-container">
    <div class="container">

        <a href="/" id="logo">اسم الموقع</a>

        <div id="search-container">
            <div class="space"></div>
            <form method="get">
                <input type="search" id="main-search-input" placeholder="عن ماذا تبحث؟"/>
                <button type="submit" class="search-submit"><i class="far fa-search"></i></button>
            </form>
            <div class="space"></div>
        </div>


        <?php
        session_start();
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != USER_TYPE_ADMIN) {
            header("Location:/");
            die();
        }

        echo "<span  id='account-menu' class='primary-color'><i class=\"fas fa-user-alt fa-lg primary-color\"> </i>" . $_SESSION['name'] . "
                    <ul>
                        <li><a href='/account.php'>إعدادات الحساب</a></li>
                        <li><a href='/admin-panel'>لوحة التحكم</a></li>
                        <li><a href='/logout.php'>تسجيل الخروج</a></li>
                    </ul>
                </span>";


        ?>

        <i class="far fa-shopping-cart fa-lg" id="shopping-cart"></i>

    </div>

    <div class="clear"></div>
</header>
<div class="header-margin"></div>


<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 27/10/18
 * Time: 01:40 م
 */

