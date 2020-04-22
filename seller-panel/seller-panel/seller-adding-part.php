<?php
$title = 'إضافة قطعة';
require_once $_SERVER['DOCUMENT_ROOT'] .'/templates/header.php';
require 'menu.php';




?>

    <link type="text/css" rel="stylesheet" href="allseller.css">
    <link type="text/css" rel="stylesheet" href="admin-panel/sytle.css">

<div class="seller-adding-part">

<form method="post" autocomplete="on">

    <table>

        <caption>إضافة المنتجات</caption>
        <tr>
            <th> اسم القطعة:</th>
            <td> <input type="text" name=part-name" class="part-name" required></td>
        </tr>
        <tr>
            <th> رقم القطعة:</th>
            <td> <input type="text" name="part-id" class="part-id" required></td>
        </tr>
        <tr>
            <th> السيارة:</th>
            <td>  <input type="text" name="car" class="cartype" required></td>
        </tr>
        <tr>
            <th> موديل السيارة:</th>
            <td> <input type="text" name="car-model" class="carmodel" required></td>
        </tr>
        <tr>
            <th> قيمة القطعة:</th>
            <td> <input type="text" name="price" class="price" required></td>
        </tr>

        <tr>
            <th class="last"><input type="submit" name="save" value="إضافة" class="submit" formnovalidate></th>
        </tr>


    </table>
</form>
    <?php
    if(isset($_POST['save']))
    {
        $partname = $_POST['part-name'];
        $partid = $_POST['part-id'];
        $car = $_POST['car'];
        $model = $_POST['car-model'];
        $price = $_POST['price'];



    }

    ?>

</div>
</body>
</html>