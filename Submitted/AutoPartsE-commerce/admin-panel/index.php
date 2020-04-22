<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 28/10/18
 * Time: 10:00 م
 */

require_once '../models/User.php';
require_once '../models/Seller.php';
require_once '../models/Part.php';
require_once '../models/Category.php';

$title = "لوحة التحكم";

require 'header.php';


$users = User::nOfUsers();
$sellers = Seller::nOfSellers();
$parts = Part::nOfParts();
$categories = Category::all(true);

?>
    <div class="display-flex">

        <?php require 'menu.php' ?>

        <div id="admin-panel-content" class="flex-column">

            <div class="block-item" id="admin-statistics">
                <h4>إحصائيات</h4>

                <table>
                    <tbody>
                    <tr>
                        <td>عدد المستخدمين:</td>
                        <td><?= $sellers + $users ?></td>
                    </tr>
                    <tr>
                        <td>&emsp; البائعين:</td>
                        <td><?= $sellers ?></td>
                    </tr>
                    <tr>
                        <td>&emsp; المشترين:</td>
                        <td><?= $users ?></td>
                    </tr>
                    <tr>
                        <td>عدد المنتجات:</td>
                        <td><?= $parts ?></td>
                    </tr>

                    </tbody>
                </table>

            </div>


            <div class="orange-block-item" id="admin-parts-statistics">
                <h4>المنتجات حسب التصنيف</h4>

                <table>
                    <tbody>
                    <?php
                    foreach ($categories as $category) {
                        printf("
                        <tr>
                            <td>%s</td>
                            <td>%d</td>
                        </tr>
                        ", $category->getName(),
                            Part::nOfPartsOfCategory($category->getId()));/*,
                            $category->getParentId() != 0 ?
                                Category::byId($category->getParentId())->getName() : "-"*/
                    }
                    ?>
                    </tbody>
                </table>

            </div>


        </div>
        <div class="clear"></div>
    </div>

<?php
require '../templates/footer.php';

