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
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/constants.php';


if (!isset($_GET['category'])) {
    header("Location:/");
}

$category = $_GET['category'];

$sub = Category::subcategoriesIds($category);
array_push($sub, $category);

$page = 1;
if (isset($_GET['page'])) $page = $_GET['page'];

$parts = Part::byCategories($sub, $page, PARTS_PER_PAGE);

$subcategories = Category::byParent($category);

$manufacturers = Manufacturer::all(1, 1000);


?>


    <div class="container content-block-container">

        <div id="block-container">
            <div class="block-item">
                <h4>تصفية حسب السيارة</h4>
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

                    <input type="hidden" name="category" value="<?php echo $category ?>"/>
                </form>
            </div>
        </div>

        <div id="content-container">


            <?php

            if (count($subcategories) > 0) {
                echo '            <div class="tag-container">';

                foreach ($subcategories as $subcategory) {
                    echo "<a href='?category=" . $subcategory->id . "' class=\"primary-colored-button tag\">" . $subcategory->name . "</a>";
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
                            <a class=\"part-item-details white-highlight-button\" href=\"/show_product.php?id=" . $part->id . "\">التفاصيل</a>
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
