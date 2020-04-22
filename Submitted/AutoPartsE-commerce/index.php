<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 27/10/18
 * Time: 01:44 م
 */


$title = 'عالم قطع الغيار';

require_once 'templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Part.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Brand.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Manufacturer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Order.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/constants.php';


$parts = Part::all(1, PARTS_PER_PAGE, false, false, true);

$manufacturers = Manufacturer::all(1, 1000);

?>

    <div class="clear"></div>
    <div id="welcome-section">
        <div class="container">
            <h3 class="title-font">مرحباً بك في موقع قطع السيارات</h3>
            <p class="body-font">لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور
                أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد
                أكسير سيتاشن يللأمكو لابورأس نيسي يت أليكيوب أكس أيا كوممودو كونسيكيوات .</p>
            <i class="fas fa-cogs fa-7x"></i>
            <div class="clear"></div>
        </div>
    </div>


    <div class="container">


        <div id="index-car-selection">

            <h3>ابحث حسب سيارتك</h3>
            <form class="car-selection-form" method="get" action="/search.php">

                <div class="input-group">

                    <label for="manufacturer-input">الماركة</label>
                    <select name='company' id="manufacturer-input">

                        <?php
                        foreach ($manufacturers as $manufacturer) {
                            echo "<option value='" . $manufacturer->id . "'>" . $manufacturer->name . "</option>";
                        }

                        ?>

                    </select>
                </div>

                <div class="input-group">
                    <label for="brand-input">نوع السيارة</label>
                    <select name="brand" id="brand-input">
                    </select>
                </div>

                <div class="input-group">
                    <label for="model-input">الموديل</label>
                    <select name="model" id="model-input">
                    </select>
                </div>

                <div class="clear"></div>

                <input type="submit" value="بحث"
                       class="primary-colored-button"/>

            </form>

        </div>
    </div>


    <div class="container content-block-container">


        <div id="block-container">
            <div class="orange-block-item">
                <h4>الأعلى بيعاً</h4>
                <ul>

                    <?php

                    $sellers = Order::top10Sellers();
                    foreach ($sellers as $seller) {
                        $sellInfo = Seller::byId($seller);

                        echo "
                                <li>
                                    <a href=\"show_seller.php?id=$sellInfo->id\">" . $sellInfo->name . "</a>
                                </li>
                             ";

                    }

                    ?>
                </ul>
            </div>

        </div>


        <div id="content-container">


            <div id="parts-container">


                <?php

                if (count($parts) > 0) {
                    foreach ($parts as $part) {

                        echo "<div class=\"part-item-container\">
                        <a class=\"part-item-link\" href=\"/show_product.php?id=$part->id\">
                            <div class=\"part-item\">";
                        if (count($part->pictures) > 0)
                            echo "<img class=\"part-image\" src=\"/files/parts/" . $part->pictures[0]->url . "\"/>";
                        else
                            echo "<img class=\"part-image\" src=\"/images/part-placeholder.png\"/>";

                        echo " 
        
                                <span class=\"part-name\">" . $part->name . "</span>
                                <div>                               
                                    <span class=\"part-price\">" . $part->price . " ريال</span>
                                </div>
                                <div class=\"clear\"></div>
                            
                            <div class=\"overlay\"></div>
                            <a class=\"part-item-details white-highlight-button\" href=\"/show_product.php?id=$part->id\">التفاصيل</a>
                        </div>
                        </a>
                    </div>";

                    }
                } else {
                    echo "<div style='padding: 15px 60px;' class='info-message margin-center text-center'><i style='color: #004085;' class=\"text-center fas fa-exclamation fa-2x\"></i><br><br>لا توجد قطع !</div>";
                }
                ?>


            </div>

            <div class="clear"></div>

        </div>


    </div>


    <div class="clear"></div>


    <script>

        var brands = [];

        <?php

        $year = date("Y");
        foreach ($manufacturers as $manufacturer) {
            $brands = Brand::byManufacturer($manufacturer->id);

            $bs = "[";

            foreach ($brands as $brand) {
                $bs .= "{id: " . $brand->id . ", name: '" . $brand->name . "', startModel: " . $brand->startModel . ", endModel:" . (isset($manufacturer->endModel) ? $manufacturer->endModel : $year) . " }, ";
            }

            echo 'brands.push(' . $bs . ']);';
        }

        ?>

        var manInput = document.getElementById("manufacturer-input");
        var brandInput = document.getElementById('brand-input');

        var modelInput = document.getElementById('model-input');


        var selectedMan;
        var selectedBrand;

        manInput.addEventListener('change', function () {

            while (brandInput.options.length) {
                brandInput.remove(0);
            }

            while (modelInput.options.length) {
                modelInput.remove(0);
            }

            selectedMan = manInput.selectedIndex;
            var mbrands = brands[selectedMan];

            for (var i = 0; i < mbrands.length; i++) {
                var brand = mbrands[i];
                brandInput.options.add(new Option(brand.name, brand.id));
            }

            brandInput.dispatchEvent(new Event('change'));
        });

        brandInput.addEventListener('change', function () {

            while (modelInput.options.length) {
                modelInput.remove(0);
            }

            selectedBrand = brandInput.selectedIndex;

            var brand = brands[selectedMan][selectedBrand];

            for (var year = brand.startModel; year <= brand.endModel; year++)
                modelInput.options.add(new Option(year, year));

        });


        manInput.dispatchEvent(new Event('change'));


    </script>


<?php
require_once 'templates/footer.php';
