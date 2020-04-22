<?php
$title = 'طلبات الزبائن';
require_once $_SERVER['DOCUMENT_ROOT'] .'/templates/header.php';
require 'menu.php';




?>

<link type="text/css" rel="stylesheet" href="allseller.css">

<div class="seller-adding-part">

    <form method="post">

        <table>

            <caption>عرض الطلبات</caption>
            <tr>
                <th><a href="">اسم العميل</a></th>
                <th><a href="">القطعة</a></th>
                <th><a href="">السيارة</a></th>
                <th><a href="">موديل السيارة</a></th>
                <th><a href="">نوع الطلب</a></th>
            </tr>

        </table>
    </form>

</div>
</body>
</html>