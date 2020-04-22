<?php
$title = 'القطع الخاصة بي';
require_once $_SERVER['DOCUMENT_ROOT'] .'/templates/header.php';
require 'menu.php';




?>

<link type="text/css" rel="stylesheet" href="allseller.css">

<div class="seller-adding-part">

    <form method="post">

        <table>

            <caption><strong>عرض القطع<strong></caption>
            <thead>
            <tr>
                <th><a href="">اسم القطعة</a></th>
                <th><a href="">رقم القطعة</a></th>
                <th><a href="">السيارة</a></th>
                <th><a href="">موديل السيارة</a></th>
                <th><a href="">سعر القطعة</a></th>

            </tr>
            </thead>
            <tbody>

            <?php

          //  require '../models/Part.php';

          //  $parts = Part::bySeller($_SESSION['id'], 1, 10);

          //  foreach ($parts as $part) {
            //    echo "
               //     <tr>
                 //       <td>$part-></td>
                  //      <td>$part->price</td>
                        
                
                  //  </tr>
              //  ";
          //  }

            ?>


            </tbody>

        </table>
    </form>

</div>
</body>
</html>