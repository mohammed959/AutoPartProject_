<!DOCTYPE html>
<html>
<head>
    <!-- Font Awesome Icon Library -->
    <link rel="stylesheet" href="../../../../../../xampp/htdocs/seller-panel/sellerpanel2.css">


</head>
<body>

<header>

    <div>


        <form name="form">
            <p id="title">عالم قطع الغيار</p>
            <input type="search" placeholder=" ابحث عن قطعتك" class="search">
            <span> اسم المستخدم </span>
            <input type="button" value="تسجيل الخروج" class="quit">


        </form>
    </div>

    <h2> لوحة تحكم البائع</h2>
</header>

<?php

// require review
$reviews = Review::bySeller($_SESSION['id'], 1, 10);
?>


</body>
</html>
