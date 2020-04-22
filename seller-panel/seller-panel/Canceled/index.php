<?php
$title = "لوحة تحكم البائع";
require_once 'header.php';
require 'menu.php';

?>

    <div class="seller-container">


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
                $category = Category::byId($part->categoryId);
                echo "
                    <tr>
                        <td>$part->name</td>
                        <td>$part->price</td>
                        <td><a href='/parts.php?category=$category->id'>$category->name</a></td>
                       
                        
                
                    </tr>
                ";
            }

            ?>

            </tbody>


        </table>

    </div>

<?php require '../templates/footer.php';