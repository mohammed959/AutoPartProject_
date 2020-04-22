<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 27/10/18
 * Time: 01:40 م
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Category.php';

$categories = Category::byParent(0);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Changa|Lateef&amp;subset=arabic" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/droid-arabic-kufi" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/css/font-awesome/css/all.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <title><?php echo $title ?></title>
</head>
<body dir="rtl">

<header id="header-container">
    <div class="container">

        <a href="/" id="logo">اسم الموقع</a>

        <div id="search-container">
            <div class="space"></div>
            <form method="get" action="/search.php">
                <input name="q" type="search" id="main-search-input"
                    <?php if (isset($_GET['q'])) echo "value='" . $_GET['q'] . "'"; ?> placeholder="عن ماذا تبحث؟"/>
                <button type="submit" class="search-submit"><i class="far fa-search"></i></button>
            </form>
            <div class="space"></div>
        </div>


        <?php
        session_start();
        if (isset($_SESSION['name'])) {
            echo "<span  id='account-menu' class='primary-color'><i class=\"fas fa-user-alt fa-lg primary-color\"> </i>" . $_SESSION['name'] . "
                    <ul>
                    <li><a href='/account.php'>إعدادات الحساب</a></li>";

            if ($_SESSION['user_type'] == USER_TYPE_ADMIN)
                echo "                    <li><a href='/admin-panel'>لوحة التحكم</a></li>";
            else if ($_SESSION['user_type'] == USER_TYPE_SELLER)
                echo "                    <li><a href='/seller-panel'>لوحة التحكم</a></li>";

            echo "
                    <li><a href='/logout.php'>تسجيل الخروج</a></li>
                    </ul>
                 </span>";
        } else {
            echo "
                <a href=\"/login.php\" class=\"primary-colored-button header-button\">تسجيل الدخول</a>
                <a href=\"/register.php\" class=\"primary-highlighted-button header-button\">تسجيل</a>
        ";
        }

        ?>

        <i class="far fa-shopping-cart fa-lg" id="shopping-cart"></i>

        <div class="clear"></div>


    </div>


</header>

<div class="header-margin"></div>

<nav>
    <div class="container">

        <ul>
            <?php
            foreach ($categories as $category) {
                echo "                <li><a href=\"/parts.php?category=" . $category->id . "\">" . $category->name . "</a></li>";
            }

            ?>
        </ul>
    </div>

    <div class="clear"></div>
</nav>


