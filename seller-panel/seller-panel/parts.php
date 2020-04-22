<?php
require 'header.php';
require 'menu.php';

?>

    <div class="seller-container">

        <h2> لوحة تحكم البائع</h2>


        <table border="1" class="table">


            <caption> منتجاتي</caption>

            <thead class="head">

            <tr>
                <th> اسم المنتج</th>
                <th> السعر</th>
                <th> التصنيف</th>
            </tr>
            </thead>
            <tbody>


            <?php

            require '../models/Part.php';

            $parts = Part::bySeller($_SESSION['id'], 1, 10);

            foreach ($parts as $part) {
                echo "
                    <tr>
                        <td>$part->name</td>
                        <td>$part->price</td>
                        
                
                    </tr>
                ";
            }

            ?>

            </tbody>


        </table>

    </div>

<?php require '../templates/footer.php';