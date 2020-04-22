<?php
/**
 * Created by PhpStorm.
 * User: malal
 * Date: 11/3/2018
 * Time: 12:00 AM
 */


require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Seller.php';


$title = "تفعيل البائعين";

define("USERS_PER_PAGE", 10);

require 'header.php';
?>
    <div class="display-flex">

        <?php require 'menu.php' ?>

        <div id="admin-panel-content" class="flex-column">


            <?php

            if (isset($_GET['delete'])) {
                $userId = $_GET['delete'];
                $user = Seller::byId($userId);
                if ($user == null) {
                    echo "
                        <div class='admin-message error-message'>
                            لم يتم العثور على العضو!
                        </div>
                        ";
                } else {
                    if ($user->delete()) {
                        echo "
                        <div class='admin-message success-message'>
                        تم رفض طلب العضو $user->name !
                        </div>
                        ";
                    }
                }
            } else if (isset($_GET['activate'])) {
                $userId = $_GET['activate'];
                $user = Seller::byId($userId);
                if ($user === null) {
                    echo "
                        <div class='admin-message error-message'>
                            لم يتم العثور على العضو!
                        </div>
                        ";
                } else {
                    $user->setStatus(Seller::$ACTIVE);
                    if ($user->update()) {
                        echo "
                        <div class='admin-message success-message'>
                        تم قبول طلب البائع $user->name
                        </div>
                        ";
                    }
                }
            }

            $page = 1;
            if (isset($_GET['page']) && $_GET['page'] > 0) $page = $_GET['page'];

            $users = Seller::getUnactivated($page, USERS_PER_PAGE);
            if (count($users) > 0) printTable();
            else {
                echo "<div class='info-message admin-message'>لا يوجد مستخدمين بحاجة إلى تفعيل!</div>";
            }


            function printTable()
            {
                echo "
                            <div class=\"admin-table-container\">
                <table cellspacing=\"0\" cellpadding=\"0\">
                    <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ التسجيل</th>
                        <th>صورة السجل التجاري</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>                
                ";

                global $users;

                foreach ($users as $user) {
                    echo "  
                            <tr>
                                <td>$user->name</td>
                                <td>$user->email</td>
                                <td>$user->registrationDate</td>
                                <td><img onclick='showImage(this)' src='/files/certificates/$user->certificate' /> </td>
                                <td><a data-name='$user->name' data-id='$user->id' onclick='confirmRefuse(this)' > <i class=\"fas fa-trash-alt red-color\"></i></a>
                                    <a data-name='$user->name' data-id='$user->id' onclick='confirmAccept(this)'><i class=\"fas fa-check green-color\"></i></a>    
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


            $nusers = Seller::getNumberOfUnactivated();
            $allPages = ceil($nusers / USERS_PER_PAGE);
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
        function confirmRefuse(element) {
            var c = confirm("هل أنت متأكد أنك تريد رفض العضو " + element.getAttribute("data-name") + "؟");
            if (c) {
                window.location = "?delete=" + element.getAttribute('data-id');
            }
        }

        function confirmAccept(element) {
            var c = confirm("هل أنت متأكد أنك تريد قبول طلب العضو " + element.getAttribute("data-name") + "؟");
            if (c) {
                window.location = "?activate=" + element.getAttribute('data-id');
            }
        }


    </script>

<?php
require '../templates/footer.php';

