<?php
/**
 * Created by PhpStorm.
 * Part: mohammad
 * Date: 30/10/18
 * Time: 09:58 ص
 */


require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Review.php';


$title = "إدارة القطع";

require 'header.php';

echo "    <div class='display-flex'>";

require 'menu.php';

echo "<div class=\"seller-container flex-column\">";


// require review
$reviews = Review::bySeller($_SESSION['id'], 1, 10);


$page = 1;
if (isset($_GET['page']) && $_GET['page'] > 0) $page = $_GET['page'];


$reviews = Review::bySeller($_SESSION['id'], $page, 10);
if (count($reviews) > 0) printTable();
else {
    echo "<div class='warning-message admin-message'>لا يوجد تقييمات!</div>";
}


function printTable()
{
    echo "
                            <div class=\"admin-table-container\">
                <table cellspacing=\"0\" cellpadding=\"0\">
                <caption>تقييمات منتجاتي</caption>
                    <thead>
                    <tr>
                        <th>التقييم</th>
                        <th>اسم المستخدم</th>
                        <th>التعليق</th>
                        <th>القطعة</th>
                        <th>الوقت</th>                        
                    </tr>
                    </thead>
                    <tbody>                
                ";

    global $reviews;

    foreach ($reviews as $review) {
        $user = User::byId($review->userId);
        $part = Part::byId($review->partId);

        echo "  
                            <tr>
                                <td>$review->rating</td>
                                <td>$user->name</td>
                                <td>$review->comment</td>
                                <td>$part->name</td>
                                <td>$review->time</td>
                            </tr>
                        ";
    }

    echo "
                
                            </tbody>
            </table>
        </div>

                
                ";

}


$nreviews = Review::numberOfSellerReviews($_SESSION['id']);
$allPages = ceil($nreviews / 10);
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
require '../templates/footer.php';

