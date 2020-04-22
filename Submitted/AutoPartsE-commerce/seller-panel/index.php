<?php
/**
 * Created by PhpStorm.
 * Part: mohammad
 * Date: 4/12/18
 * Time: 09:58 ص
 */


require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Part.php';


$title = "إدارة القطع";

require 'header.php';


echo "    <div class='display-flex'>";

require 'menu.php';

echo "<div class=\"seller-container flex-column\">";


if (isset($_GET['delete'])) {
    $partId = $_GET['delete'];
    $part = Part::byId($partId);
    if ($part == null) {
        echo "
                        <div class='admin-message error-message'>
                            لم يتم العثور على القطعة!
                        </div>
                        ";
    } else if ($part->sellerId != $_SESSION['id']) {
        echo "
                        <div class='admin-message error-message'>
                            لا تملك صلاحية حذف القطعة!
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


$page = 1;
if (isset($_GET['page']) && $_GET['page'] > 0) $page = $_GET['page'];


$parts = Part::bySeller($_SESSION['id'], $page, PARTS_PER_PAGE);
if (count($parts) > 0) printTable();
else {
    echo "<div class='warning-message admin-message'>لا يوجد قطع!</div>";
}


function printTable()
{
    echo "
                            <div class=\"admin-table-container\">
                <table cellspacing=\"0\" cellpadding=\"0\">
                <caption>منتجاتي</caption>
                    <thead>
                    <tr>
                        <th>القطعة</th>
                        <th>الوصف</th>
                        <th>السعر</th>
                        <th>التصنيف</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>                
                ";

    global $parts;

    foreach ($parts as $part) {
        $category = Category::byId($part->categoryId);
        echo "  
                            <tr>
                                <td><a href=\"#\">$part->name</a></td>
                                <td>$part->description</td>
                                <td>$part->price</td>
                                <td>" . $category->name . "</td>                         
                                <td><a data-name='$part->name' data-id='$part->id' onclick='confirmDelete(this)' > <i class=\"fas fa-trash-alt red-color\"></i></a>  <a href='part.php?edit=1&id=$part->id'><i class='fa fa-edit primary-color'></i> </a> </td>
                            </tr>
                        ";
    }

    echo "
                
                            </tbody>
            </table>
        </div>

                
                ";

}


$nparts = Part::nOfPartsOfSeller($_SESSION['id']);
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

