<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 28/10/18
 * Time: 11:45 م
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/User.php';


$title = "إدارة المشترين";

define('USERS_PER_PAGE', 10);

require 'header.php';
?>
    <div class="display-flex">

        <?php require 'menu.php' ?>

        <div id="admin-panel-content" class="flex-column">


            <?php

            if (isset($_GET['delete'])) {
                $userId = $_GET['delete'];
                $user = User::byId($userId);
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
                        تم حذف العضو $user->name بنجاح!
                        </div>
                        ";
                    }
                }
            } else if (isset($_GET['block'])) {
                $userId = $_GET['block'];
                $user = User::byId($userId);
                if ($user === null) {
                    echo "
                        <div class='admin-message error-message'>
                            لم يتم العثور على العضو!
                        </div>
                        ";
                } else {
                    $user->setStatus(User::$BLOCKED);
                    if ($user->update()) {
                        echo "
                        <div class='admin-message success-message'>
                        تم حظر العضو $user->name بنجاح!
                        </div>
                        ";
                    }
                }
            } else if (isset($_GET['unblock'])) {
                $userId = $_GET['unblock'];
                $user = User::byId($userId);
                if ($user === null) {
                    echo "
                        <div class='admin-message error-message'>
                            لم يتم العثور على العضو!
                        </div>
                        ";
                } else {
                    $user->setStatus(User::$ACTIVE);
                    if ($user->update()) {
                        echo "
                        <div class='admin-message success-message'>
                        تم إلغاء حظر العضو $user->name بنجاح!
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
                $users = User::search($query, $page, USERS_PER_PAGE);

                if (count($users) > 0) printTable();
                else {
                    echo "<div class='warning-message admin-message'>لم يتم العثور على نتائج!</div>";
                }
            } else {
                $users = User::all($page, 10);
                if (count($users) > 0) printTable();
                else {
                    echo "<div class='warning-message admin-message'>لا يوجد مستخدمين!</div>";
                }
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
                        <th>حالة العضو</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>                
                ";

                global $users;

                foreach ($users as $user) {
                    echo "  
                            <tr>
                                <td><a href=\"#\">$user->name</a></td>
                                <td>$user->email</td>
                                <td>$user->registrationDate</td>
                                <td>";

                    switch ($user->status) {
                        case User::$BLOCKED:
                            echo "محظور";
                            break;

                        case User::$ACTIVE:
                            echo "فعال";
                            break;
                    }

                    echo "</td>
                                <td><a data-name='$user->name' data-id='$user->id' onclick='confirmDelete(this)' > <i class=\"fas fa-trash-alt red-color\"></i></a>";
                    if ($user->status == User::$ACTIVE) {
                        echo "                        <a data-name='$user->name' data-id='$user->id' onclick='confirmBlock(this)'><i class=\"fas fa-ban red-color\"></i></a>";
                    } else {
                        echo "                        <a data-name='$user->name' data-id='$user->id' onclick='confirmUnblock(this)'><i class=\"fas fa-check green-color\"></i></a>";
                    }


                    echo "
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
                $nusers = User::nOfUsers($query);
            else
                $nusers = User::nOfUsers();
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
        function confirmDelete(element) {
            var c = confirm("هل أنت متأكد أنك تريد حذف العضو " + element.getAttribute("data-name") + "؟");
            if (c) {
                window.location = "?delete=" + element.getAttribute('data-id');
            }
        }

        function confirmBlock(element) {
            var c = confirm("هل أنت متأكد أنك تريد حظر العضو " + element.getAttribute("data-name") + "؟");
            if (c) {
                window.location = "?block=" + element.getAttribute('data-id');
            }
        }

        function confirmUnblock(element) {
            var c = confirm("هل أنت متأكد أنك تريد إلغاء حظر العضو " + element.getAttribute("data-name") + "؟");
            if (c) {
                window.location = "?unblock=" + element.getAttribute('data-id');
            }
        }

    </script>

<?php
require '../templates/footer.php';

