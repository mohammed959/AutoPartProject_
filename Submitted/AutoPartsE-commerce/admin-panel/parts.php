<?php
/**
 * Created by PhpStorm.
 * Part: mohammad
 * Date: 30/10/18
 * Time: 09:58 ص
 */


require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Part.php';


$title = "إدارة القطع";

require 'header.php';

define("PARTS_PER_PAGE", 10);

?>
    <div class="display-flex">

        <?php require 'menu.php' ?>

        <div id="admin-panel-content" class="flex-column">


            <?php

            if (isset($_GET['delete'])) {
                $partId = $_GET['delete'];
                $part = Part::byId($partId);
                if ($part == null) {
                    echo "
                        <div class='admin-message error-message'>
                            لم يتم العثور على القطعة!
                        </div>
                        ";
                } else {
                    if ($part->delete()) {
                        echo "
                        <div class='admin-message success-message'>
                        تم حذف القطعة $part->name بنجاح!
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
            echo " placeholder=\"بحث عن قطعة\"/>
                    <button class=\"search-submit\"><i class=\"far fa-search\"></i></button>
                </form>
            </div>";


            $page = 1;
            if (isset($_GET['page']) && $_GET['page'] > 0) $page = $_GET['page'];

            if (isset($query)) {
                $parts = Part::search($query, $page, PARTS_PER_PAGE, true, true);

                if (count($parts) > 0) printTable();
                else {
                    echo "<div class='warning-message admin-message'>لم يتم العثور على نتائج!</div>";
                }
            } else {

                $parts = Part::all($page, PARTS_PER_PAGE, true, true);
                if (count($parts) > 0) printTable();
                else {
                    echo "<div class='warning-message admin-message'>لا يوجد قطع!</div>";
                }
            }


            function printTable()
            {
                echo "
                            <div class=\"admin-table-container\">
                <table cellspacing=\"0\" cellpadding=\"0\">
                    <thead>
                    <tr>
                        <th>القطعة</th>
                        <th>الوصف</th>
                        <th>السعر</th>
                        <th>البائع</th>
                        <th>التصنيف</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>                
                ";

                global $parts;

                foreach ($parts as $part) {
                    echo "  
                            <tr>
                                <td><a href=\"#\">$part->name</a></td>
                                <td>$part->description</td>
                                <td>$part->price</td>
                                <td>" . $part->seller->name . "</td>
                                <td>" . $part->category->name . "</td>                         
                                <td><a data-name='$part->name' data-id='$part->id' onclick='confirmDelete(this)' > <i class=\"fas fa-trash-alt red-color\"></i></a></td>
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
                $nparts = Part::nOfParts($query);
            else
                $nparts = Part::nOfParts();
            $allPages = ceil($nparts / PARTS_PER_PAGE);
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
            var c = confirm("هل أنت متأكد أنك تريد حذف القطعة " + element.getAttribute("data-name") + "؟");
            if (c) {
                window.location = "?delete=" + element.getAttribute('data-id');
            }
        }

    </script>

<?php
require '../templates/footer.php';

