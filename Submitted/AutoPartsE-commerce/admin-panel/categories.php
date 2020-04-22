<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 30/10/18
 * Time: 05:19 م
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Category.php';


$title = "إدارة القطع";

require 'header.php';


?>
    <div class="display-flex">

        <?php require 'menu.php' ?>

        <div id="admin-panel-content" class="flex-column">


            <?php

            if (isset($_GET['delete'])) {
                $categoryId = $_GET['delete'];
                $category = Category::byId($categoryId);
                if ($category == null) {
                    echo "
                        <div class='admin-message error-message'>
                            لم يتم العثور على التصنيف!
                        </div>
                        ";
                } else {
                    if ($category->delete()) {
                        echo "
                        <div class='admin-message success-message'>
                        تم حذف التصنيف $category->name بنجاح!
                        </div>
                        ";
                    }
                }
            }


            echo "<div class=\"admin-search search-container\">
                <form>
                    <input type=\"search\" name=\"search\" class=\"input body-font\"";

            if (isset($_GET['search'])) {
                $query = $_GET['search'];
                echo ' value="' . $query . '"';
            }
            echo " placeholder=\"بحث عن مستخدم\"/>
                    <button class=\"search-submit\"><i class=\"far fa-search\"></i></button>
                </form>
            </div>";

            $page = 1;
            if (isset($_GET['page']) && $_GET['page'] > 0) $page = $_GET['page'];

            if (isset($query)) {
                $categorys = Category::search($query);

                if (count($categorys) > 0) printTable();
                else {
                    echo "<div class='warning-message admin-message'>لم يتم العثور على نتائج!</div>";
                }
            } else {

                $categorys = Category::all(true);
                if (count($categorys) > 0) printTable();
                else {
                    echo "<div class='warning-message admin-message'>لا يوجد تصنيفات!</div>";
                }
            }


            function printTable()
            {
                echo "
                            <div class=\"admin-table-container\">
                <table cellspacing=\"0\" cellpadding=\"0\">
                    <thead>
                    <tr>
                        <th>التصنيف</th>
                        <th>التصنيف الأب</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>                
                ";

                global $categorys;

                foreach ($categorys as $category) {
                    echo "  
                            <tr>
                                <td><a href=\"#\">$category->name</a></td>
                                <td><a href=\"#\">
                                ";

                    if ($category->parentId == 0)

                        echo "-";
                    else
                        echo $category->parent()->name;
                    echo "
                                </a></td>
                                <td>
                                    <a data-name='$category->name' data-id='$category->id' onclick='confirmDelete(this)' > <i class=\"fas fa-trash-alt red-color\"></i></a>
                                    <a href='category.php?edit=$category->id'> <i class=\"fas fa-edit primary-color\"></i></a>
                                </td>
                            </tr>
                        ";
                }

                echo "
                
                            </tbody>
            </table>
        </div>

                
                ";

            }

            ?>


        </div>
        <div class="clear"></div>
    </div>


    <script>
        function confirmDelete(element) {
            var c = confirm("هل أنت متأكد أنك تريد حذف التصنيف " + element.getAttribute("data-name") + "؟");
            if (c) {
                window.location = "?delete=" + element.getAttribute('data-id');
            }
        }

    </script>

<?php
require '../templates/footer.php';

