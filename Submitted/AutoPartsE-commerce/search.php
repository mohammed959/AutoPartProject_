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
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/constants.php';


if (isset($_GET['brand'])) $brand = $_GET['brand'];
if (isset($_GET['model'])) $model = $_GET['model'];


$page = 1;
if (isset($_GET['page'])) $page = $_GET['page'];

if (isset($_GET['category'])) {

    if (!isset($brand) || !isset($model)) header("Location:/");
    $category = $_GET['category'];

    $sub = Category::subcategoriesIds($category);
    array_push($sub, $category);

    $parts = Part::byCategoriesAndCar($sub, $brand, $model, $page, PARTS_PER_PAGE);

    $subcategories = Category::byParent($category);

} else if (isset($_GET['q'])) {
    $query = $_GET['q'];
    if (isset($brand) && isset($model)) {
        $parts = Part::searchWithCar($query, $brand, $model, $page, PARTS_PER_PAGE);
    } else {
        $parts = Part::search($query, $page, PARTS_PER_PAGE);
    }

} else if (isset($brand) && isset($model)) {
    $parts = Part::byCar($brand, $model, $page, PARTS_PER_PAGE);
} else {
    header("Location:/");
}


require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Manufacturer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Brand.php';
$manufacturers = Manufacturer::all(1, 1000);
?>


    <div class="container content-block-container">

        <div id="block-container">
            <div class="block-item">
                <h4>تصفية حسب السيارة</h4>
                <form class="car-selection-form" method="get" action="/search.php">

                    <div class="input-group">

                        <label for="manufacturer-input">الماركة</label>
                        <select name="company" id="manufacturer-input">

                            <?php
                            foreach ($manufacturers as $manufacturer) {
                                echo "<option value='" . $manufacturer->id . "' ";
                                if (isset($_GET['company']) && $_GET['company'] == $manufacturer->id) echo 'selected ';
                                echo ">" . $manufacturer->name . "</option>";
                            }

                            ?>

                        </select>
                    </div>

                    <br>

                    <div class="input-group">
                        <label for="brand-input">نوع السيارة</label>
                        <select name="brand" id="brand-input">
                        </select>
                    </div>

                    <br>
                    <div class="input-group">
                        <label for="model-input">الموديل</label>
                        <select name="model" id="model-input">
                        </select>
                    </div>

                    <div class="clear"></div>

                    <input type="submit" value="بحث"
                           class="primary-colored-button"/>

                    <?php
                    if (isset($_GET['q'])) {
                        echo "                    <input type=\"hidden\" name=\"q\" value=\"" . $_GET['q'] . "\"/>";
                    } else if (isset($_GET['category'])) {
                        echo "                    <input type='hidden' name='category' value='" . $_GET['category'] . "' />";
                    }

                    ?>
                </form>
            </div>
        </div>

        <div id="content-container">


            <?php
            if (isset($subcategories) && count($subcategories) > 0) {
                echo '            <div class="tag-container">';

                parse_str($_SERVER['QUERY_STRING'], $query_string);

                foreach ($subcategories as $subcategory) {
                    $query_string['category'] = $subcategory->id;

                    echo "<a href='?" . http_build_query($query_string) . "' class=\"primary-colored-button tag\">" . $subcategory->name . "</a>";
                }
                echo '        </div>';
            }
            ?>


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


            <?php
            if (count($parts) == PARTS_PER_PAGE) {
                parse_str($_SERVER['QUERY_STRING'], $query_string);
                $query_string['page'] = $page + 1;
                echo "            <a href=\"?" . http_build_query($query_string) . "\" class=\"primary-colored-button margin-10 float-left\">
                عرض المزيد
            </a>
            ";
            }

            ?>
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

        var changed = false;

        var manInput = document.getElementById("manufacturer-input");
        var brandInput = document.getElementById('brand-input');

        var modelInput = document.getElementById('model-input');


        var selectedMan;
        var selectedBrand;

        var url_string = window.location.href;
        var url = new URL(url_string);
        var brandParam, modelParam;
        if (url.searchParams.has('brand')) {
            brandParam = url.searchParams.get('brand');
            modelParam = url.searchParams.get('model');
        }


        manInput.addEventListener('change', function () {
            while (brandInput.options.length) {
                brandInput.remove(0);
            }

            while (modelInput.options.length) {
                modelInput.remove(0);
            }


            selectedMan = manInput.selectedIndex;

            var mbrands = brands[selectedMan];


            var sindex = 0;
            for (var i = 0; i < mbrands.length; i++) {
                var brand = mbrands[i];
                if (!changed && brandParam !== undefined && brandParam == brand.id) {
                    sindex = i;
                }
                brandInput.options.add(new Option(brand.name, brand.id));
            }
            brandInput.selectedIndex = sindex;

            brandInput.dispatchEvent(new Event('change'));
        });

        brandInput.addEventListener('change', function () {

            while (modelInput.options.length) {
                modelInput.remove(0);
            }

            selectedBrand = brandInput.selectedIndex;

            var brand = brands[selectedMan][selectedBrand];

            var sindex = 0;
            for (var year = brand.startModel, i = 0; year <= brand.endModel; year++, i++) {
                if (!changed && modelParam !== undefined && modelParam == year) sindex = i;
                modelInput.options.add(new Option(year, year));
            }
            modelInput.selectedIndex = sindex;

            changed = true;
        });


        manInput.dispatchEvent(new Event('change'));


    </script>


<?php
require_once 'templates/footer.php';
