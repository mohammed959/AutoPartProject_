<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 30/10/18
 * Time: 11:22 م
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Manufacturer.php';


$title = "إدارة الشركات";

require 'header.php';

define("MANU_PER_PAGE", 10);

?>
    <div class="display-flex">

        <?php require 'menu.php' ?>

        <div id="admin-panel-content" class="flex-column">


            <?php

            if (isset($_GET['delete'])) {
                $manuId = $_GET['delete'];
                $manu = Manufacturer::byId($manuId);
                if ($manu == null) {
                    echo "
                        <div class='admin-message error-message'>
                            لم يتم العثور على الشركة!
                        </div>
                        ";
                } else {
                    if ($manu->delete()) {
                        echo "
                        <div class='admin-message success-message'>
                        تم حذف الشركة $manu->name بنجاح!
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
            echo " placeholder=\"بحث عن شركة\"/>
                    <button class=\"search-submit\"><i class=\"far fa-search\"></i></button>
                </form>
            </div>";


            $page = 1;
            if (isset($_GET['page']) && $_GET['page'] > 0) $page = $_GET['page'];

            if (isset($query)) {
                $manus = Manufacturer::search($query, $page, MANU_PER_PAGE);

                if (count($manus) > 0) printTable();
                else {
                    echo "<div class='warning-message admin-message'>لم يتم العثور على نتائج!</div>";
                }
            } else {
                $manus = Manufacturer::all($page, MANU_PER_PAGE);
                if (count($manus) > 0) printTable();
                else {
                    echo "<div class='warning-message admin-message'>لا يوجد شركات!</div>";
                }
            }


            function printTable()
            {
                echo "
                            <div class=\"admin-table-container\">
                <table cellspacing=\"0\" cellpadding=\"0\">
                    <thead>
                    <tr>
                        <th>الشعار</th>
                        <th>الشركة</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>                
                ";

                global $manus;

                foreach ($manus as $manu) {
                    echo "  
                            <tr>
                                <td><img class='preview-image' src='/files/logos/$manu->logoUrl' /> </td>
                                <td><a href=\"#\">$manu->name</a></td>
                               
                                <td>
                                    <a data-name='$manu->name' data-id='$manu->id' onclick='confirmDelete(this)' > <i class=\"fas fa-trash-alt red-color\"></i></a>
                                    <a href='manufacturer.php?edit=$manu->id'> <i class=\"fas fa-edit primary-color\"></i></a>
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
                $nmanus = Manufacturer::nOfManus($query);
            else
                $nmanus = Manufacturer::nOfManus();
            $allPages = ceil($nmanus / MANU_PER_PAGE);
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
            var c = confirm("هل أنت متأكد أنك تريد حذف الشركة " + element.getAttribute("data-name") + "؟");
            if (c) {
                window.location = "?delete=" + element.getAttribute('data-id');
            }
        }

    </script>

<?php
require '../templates/footer.php';

