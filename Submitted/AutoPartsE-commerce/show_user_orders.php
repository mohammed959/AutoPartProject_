<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11/21/2018
 * Time: 8:44 PM
 */

$title = 'طلباتي';
require_once $_SERVER['DOCUMENT_ROOT'].'/templates/header.php';

echo "
    <p class=\"name-head\">طلباتي</p>
    
    ";

//echo "</body></html>";
?>

<table>
    <tr>
        <th>الطلب</th>
        <th>الكمية</th>
        <th>السعر</th>
        <th>خيارات</th>
    </tr>
    <tr>
        <td>
            <div>
                <img class="" src="/images/alternator.jpg" alt="الصورة غير متوفرة">
                <p>دينمو أصلي مش حتحصل مثله</p>
            </div>
        </td>
        <td>10</td>
        <td>10 ريال</td>
        <td style="color: red">حذف الطلب</td>
    </tr>
    <tr>
        <td>
            <div>
                <img class="" src="/images/alternator.jpg" alt="الصورة غير متوفرة">
                <p>دينمو أصلي مش حتحصل مثله</p>
            </div>
        </td>
        <td>10</td>
        <td>10 ريال</td>
        <td style="color: red"><a href="">حذف الطلب</a></td>
    </tr>
</table>
</body>
</html>
