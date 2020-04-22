<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 30/10/18
 * Time: 08:43 م
 */


require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Brand.php';


$title = "إدارة السيارات";

require 'header.php';

define("CARS_PER_PAGE", 10);

?>
    <div class="display-flex">

        <?php require 'menu.php' ?>

        <div id="admin-panel-content" class="flex-column">


            <?php

            if (isset($_GET['delete'])) {
                $brandId = $_GET['delete'];
                $brand = Brand::byId($brandId);
                if ($brand == null) {
                    echo "
                        <div class='admin-message error-message'>
                            لم يتم العثور على السيارة!
                        </div>
                        ";
                } else {
                    if ($brand->delete()) {
                        echo "
                        <div class='admin-message success-message'>
                        تم حذف السيارة $brand->name بنجاح!
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
            echo " placeholder=\"بحث عن سيارة\"/>
                    <button class=\"search-submit\"><i class=\"far fa-search\"></i></button>
                </form>
            </div>";


            $page = 1;
            if (isset($_GET['page']) && $_GET['page'] > 0) $page = $_GET['page'];

            if (isset($query)) {
                $brands = Brand::search($query, $page, CARS_PER_PAGE);

                if (count($brands) > 0) printTable();
                else {
                    echo "<div class='warning-message admin-message'>لم يتم العثور على نتائج!</div>";
                }
            } else {
                $brands = Brand::all($page, CARS_PER_PAGE);
                if (count($brands) > 0) printTable();
                else {
                    echo "<div class='warning-message admin-message'>لا يوجد سيارات!</div>";
                }
            }


            function printTable()
            {
                echo "
                            <div class=\"admin-table-container\">
                <table cellspacing=\"0\" cellpadding=\"0\">
                    <thead>
                    <tr>
                        <th>السيارة</th>
                        <th>الشركة المصنعة</th>
                        <th>أول موديل</th>
                        <th>آخر موديل</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>                
                ";

                global $brands;

                foreach ($brands as $brand) {
                    echo "  
                            <tr>
                                <td><a href=\"#\">$brand->name</a></td>
                                <td>" . $brand->manufacturer()->name . "</td>
                                <td>$brand->startModel</td>
                                <td>" . ($brand->endModel == null ? "-" : $brand->endModel) . "</td>
                                <td>
                                    <a data-name='$brand->name' data-id='$brand->id' onclick='confirmDelete(this)' > <i class=\"fas fa-trash-alt red-color\"></i></a>
                                    <a href='car.php?edit=$brand->id'> <i class=\"fas fa-edit primary-color\"></i></a>
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


            if (isset($query))
                $nbrands = Brand::nOfCars($query);
            else
                $nbrands = Brand::nOfCars();
            $allPages = ceil($nbrands / CARS_PER_PAGE);
            if ($page <= $allPages && $allPages > 1) {


                echo "<div class=\"admin-pagination pagination-container\">";

                parse_str($_SERVER['QUERY_STRING'], $query_string);

                if ($page > 1) {
                    $query_string['page'] = $page - 1;
                    printf("<a href=\"?%s\" class=\"light-orange-colored-button pagination-button\">
                                    <i class=\"fas   fa-angle-right\"></i>
                                </a>
                            ", http_build_query($query_string));
                }

                $pages = min($allPages, 10);
                if ($pages < 10) {
                    $before = $page - 1;
                } else {
                    $before = min($page - 1, $pages / 2);
                }
                $after = $pages - $before - 1;

                for ($i = $after; $i > 0; $i--) {
                    $query_string['page'] = $i + $page;
                    echo "<a class=\"pagination-item\" href=\"?" . http_build_query($query_string) . "\">" . ($i + $page) . "</a>";
                }

                echo "<span class=\"pagination-item pagination-item-active\">$page</span>";

                for ($i = 1; $i <= $before; $i++) {
                    $query_string['page'] = $page - $i;
                    echo "<a class=\"pagination-item\" href=\"?" . http_build_query($query_string) . "\">" . ($page - $i) . "</a>";
                }


                if ($page < $allPages) {
                    $query_string['page'] = $page + 1;
                    printf("<a href=\"?%s\" class=\"light-orange-colored-button pagination-button\">
                                <i class=\"fas fa-angle-left\"></i>
                            </a>", http_build_query($query_string));
                }
                echo "        </div>";
            }
            ?>


        </div>
        <div class="clear"></div>
    </div>


    <script>
        function confirmDelete(element) {
            var c = confirm("هل أنت متأكد أنك تريد حذف السيارة " + element.getAttribute("data-name") + "؟");
            if (c) {
                window.location = "?delete=" + element.getAttribute('data-id');
            }
        }

    </script>

<?php
require '../templates/footer.php';

