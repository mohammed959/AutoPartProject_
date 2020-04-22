<?php
/**
 * Created by PhpStorm.
 * User: malal
 * Date: 11/12/2018
 * Time: 7:32 AM
 */


require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Part.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Brand.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/PartPicture.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/PartCar.php';
require_once '../utils/files_utils.php';
require_once '../utils/constants.php';


if (isset($_GET['edit'])) {
    $partEdit = Part::byId($_GET['id']);
    if (!isset($partEdit)) {
        $message = 'لم يتم العثور على القطعة!';
        $class = 'error-message';
    }
    $title = "تعديل قطعة";
} else {
    $title = "إضافة قطعة";
}
require '../templates/header.php';

if ($_SESSION['user_type'] != USER_TYPE_SELLER) {
    header("Location:/");
    die();
}

if (isset($_POST['save'])) {
    save(isset($_POST['edit']));
}


function save($edit)
{
    global $message, $class, $partEdit;
    if ($edit) {
        $partId = $_POST['id'];
        if (!isset($partId)) {
            $message = 'يرجى اختيار قطعة !';
            $class = 'error-message';
            return;
        }
    }

    $name = $_POST['name'];

    if (strlen($name) < 5) {
        $message = 'الاسم قصير جداً!';
        $class = 'error-message';
        return;
    }

    $desc = $_POST['desc'];

    if (strlen($desc) < 5) {
        $message = 'الوصف قصير جداً!';
        $class = 'error-message';
        return;
    }

    $category = $_POST['category'];

    if (!isset($category)) {
        $message = 'يرجى اختيار تصنيف!';
        $class = 'error-message';
        return;
    }

    $carsCount = $_POST['cars_count'];
    if (!$edit) {
        if ($carsCount == 0) {
            $message = "يرجى اختيار سيارة واحدة على الأقل!";
            $class = 'error-message';
            return;
        }
    }
    $price = $_POST['price'];

    if (!isset($price) || $price < 0) {
        $message = 'يرجى إدخال سعر صالح!';
        $class = 'error-message';
        return;
    }

    if ($edit) {
        $part = Part::byId($partId);

        if ($part->sellerId != $_SESSION['id']) {
            $message = 'لا تملك صلاحية التعديل على هذه القطعة!';
            $class = 'error-message';
            return;
        }
        $part->setName($name);
        $part->setCategoryId($category);
        $part->setDescription($desc);
        $part->setPrice($price);

        $part->update();
    } else {
        $part = new Part($name, $desc, $price, $_SESSION['id'], $category);
        $partId = $part->create();
    }

    for ($i = 1; $i <= $carsCount; $i++) {
        if (isset($_POST['man-' . $i])) {
            $partCar = new PartCar($partId, $_POST['brand-' . $i], $_POST['start-model-' . $i], $_POST['end-model-' . $i]);
            $partCar->create();
        }
    }

    $picturesCount = $_POST['pictures_count'];
    for ($i = 1; $i <= $picturesCount; $i++) {
        if (isFileSet($_FILES['picture-' . $i])) {
            $url = uploadImage($_FILES['picture-' . $i], $_SERVER['DOCUMENT_ROOT'] . '/files/parts');
            $partPicture = new PartPicture($partId, $url);
            $partPicture->create();
        }
    }

    if (isset($partEdit)) {
        if (!($_POST['removedPictures'] == '')) {
            $removedPictures = json_decode($_POST['removedPictures']);

            foreach ($removedPictures as $picture) {
                $pp = PartPicture::byId($picture);
                if ($pp)
                    $pp->delete();
            }
        }

        if ($_POST['removedCars'] != '') {
            $carsToRemove = json_decode($_POST['removedCars'], true);
            foreach ($carsToRemove as $car) {
                $partCar = new PartCar($partEdit->id, $car["brand"], $car["startModel"], $car["endModel"]);
                $partCar->delete();
            }
        }

    }


}

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Category.php';
$categories = Category::all(true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Manufacturer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Brand.php';
$manufacturers = Manufacturer::all(1, 1000);
?>

    <div class="container">

        <?php

        if (isset($message)) {
            echo "<div class='$class'>$message</div>";
        }

        ?>


        <form method="post" class="seller-part-form" enctype="multipart/form-data">

            <div class="input-group">
                <label for="name-input">اسم المنتج</label>
                <input name="name" id="name-input" class="input" required
                       type="text" <?php if (isset($partEdit)) echo "value='$partEdit->name'"; ?>/>
            </div>

            <br>

            <div class="input-group">
                <label for="desc-input">وصف المنتج</label>
                <textarea name="desc" id="desc-input"
                          required><?php if (isset($partEdit)) echo $partEdit->description; ?></textarea>
            </div>

            <br>


            <div class="input-group">
                <label for="price-input">السعر</label>
                <input name="price" id="price-input"
                       class="input" <?php if (isset($partEdit)) echo "value='$partEdit->price'" ?> required
                       type="text"/>
            </div>

            <br>

            <div class="input-group">
                <label for="category-input">التصنيف</label>
                <select id="category-input" name="category">
                    <?php foreach ($categories as $category) {
                        echo "                    <option " . (isset($partEdit) && $partEdit->categoryId == $category->id ? "selected" : "") . "value=\"$category->id\">$category->name</option>";
                    }
                    ?>
                </select>
            </div>

            <br>

            <h3>السيارة</h3>
            <?php if (isset($partEdit)) {

                echo "<div id=\"previous-cars\">";

                $cars = PartCar::carsOfPart($partEdit->id);
                foreach ($cars as $car) {
                    $brand = Brand::byId($car->brandId);
                    echo "<div class='prev-car'>$brand->name &nbsp;&nbsp;&nbsp;&nbsp; $car->startModel-$car->endModel <i onclick='removePrevCar(this)' data-brand='$car->brandId' data-startModel='$car->startModel' data-endModel='$car->endModel' class='fa fa-times orange-color'></i> </div>";
                }

                echo "            </div>";

            }
            ?>

            <div id="cars-container">


            </div>

            <button onclick="addCar()" id="add-image-field" type="button" class="light-orange-colored-button"><i
                        class="fa fa-plus white-color"></i></button>


            <h3>صور المنتج</h3>
            <?php if (isset($partEdit)) {

                echo "<div id=\"previous-images\">";

                $pictures = PartPicture::byPart($partEdit->id);
                foreach ($pictures as $picture) {
                    echo "<div class='prev-image'><img src='/files/parts/$picture->url' /> <i onclick='removePrevImage(this)' data-id='$picture->id' class='fa fa-times orange-color'></i> </div>";
                }

                echo "            </div>";

            }
            ?>


            <div id="pictures-container">

            </div>
            <button onclick="addPicture()" id="add-image-field" type="button" class="light-orange-colored-button"><i
                        class="fa fa-plus white-color"></i></button>


            <div class="clear"></div>

            <input type="hidden" name="cars_count" id="cars-count" value="0"/>
            <input type="hidden" name="pictures_count" id="pictures-count" value="0"/>


            <?php

            if (isset($partEdit)) {
                echo "<input type='hidden' name='removedCars' value='' />";
                echo "<input type='hidden' name='removedPictures' value='' />";
                echo "<input type='hidden' name='edit' value='1' />";
                echo "<input type='hidden' name='id' value='$partEdit->id' />";
            }

            ?>

            <input class="primary-colored-button margin-center display-block" type="submit" name="save" value="حفظ"/>

        </form>
    </div>


    <script>

        var brands = [];
        var mans = [];

        <?php


        $year = date("Y");
        foreach ($manufacturers as $manufacturer) {


            echo "mans.push({id: $manufacturer->id, name: '$manufacturer->name'});";

            $brands = Brand::byManufacturer($manufacturer->id);

            $bs = "[";

            foreach ($brands as $brand) {
                $bs .= "{id: " . $brand->id . ", name: '" . $brand->name . "', startModel: " . $brand->startModel . ", endModel:" . (isset($manufacturer->endModel) ? $manufacturer->endModel : $year) . " }, ";
            }

            echo 'brands.push(' . $bs . ']);';
        }

        ?>




        function manChanged(element) {
            console.log(element.getAttribute('data-i'));
            var i = element.getAttribute('data-i');

            var brandInput = document.getElementsByName('brand-' + i)[0];
            var modelInput = document.getElementsByName('start-model-' + i)[0];

            while (brandInput.options.length) {
                brandInput.remove(0);
            }

            while (modelInput.options.length) {
                modelInput.remove(0);
            }
            var mbrands = brands[element.selectedIndex];

            for (var i = 0; i < mbrands.length; i++) {
                var brand = mbrands[i];
                brandInput.options.add(new Option(brand.name, brand.id));
            }
            brandInput.dispatchEvent(new Event('change'));
        }


        function brandChanged(element) {
            var i = element.getAttribute('data-i');
            var manInput = document.getElementsByName('man-' + i)[0];
            var modelInput = document.getElementsByName('start-model-' + i)[0];


            while (modelInput.options.length) {
                modelInput.remove(0);
            }

            var brand = brands[manInput.selectedIndex][element.selectedIndex];

            for (var year = brand.startModel, i = 0; year <= brand.endModel; year++, i++) {
                modelInput.options.add(new Option(year, year));
            }

            modelInput.dispatchEvent(new Event('change'));
        }


        var carContainer = document.getElementById("cars-container");
        var picturesContainer = document.getElementById("pictures-container");
        var picturesCountInput = document.getElementById('pictures-count');
        var carsCountInput = document.getElementById('cars-count');

        var carCount = 0;
        var picturesCount = 0;

        function addCar() {
            carCount++;
            var content = "                <div id='car-container-" + carCount + "'>\n" +
                "                    الماركة:\n" +
                "                    &nbsp;&nbsp;&nbsp;&nbsp;\n" +
                "                    <select data-i='" + carCount + "' onchange='manChanged(this)' name='man-" + carCount + "' class='man'>\n";

            for (var i = 0; i < mans.length; i++) {
                content += "<option value='" + mans[i].id + "'>" + mans[i].name + "</option>";
            }

            content += "                    </select>\n" +
                "\n" +
                "                    &nbsp;&nbsp;&nbsp;&nbsp;\n" +
                "                    النوع:\n" +
                "                    &nbsp;&nbsp;&nbsp;&nbsp;\n" +
                "                    <select onchange='brandChanged(this)' data-i='" + carCount + "' name='brand-" + carCount + "' class='brand'>\n" +
                "                        <option></option>\n" +
                "                    </select>\n" +
                "<br>" +
                "                    &nbsp;&nbsp;&nbsp;&nbsp;\n" +
                "                    الموديل:\n" +
                "                    &nbsp;&nbsp;&nbsp;&nbsp;\n" +
                "من:" +
                "                    <select onchange='startModelChanged(this)' ' data-i='" + carCount + "' name='start-model-" + carCount + "' class='model'>\n" +
                "                        <option></option>\n" +
                "                    </select>\n" +
                "\n" +
                "إلى:" +
                "                    <select data-i='" + carCount + "' name='end-model-" + carCount + "' class='model'>\n" +
                "                        <option></option>\n" +
                "                    </select>\n" +
                "\n" +
                "                    <button onclick='removeCar(this)' data-i='" + carCount + "' type='button' class='button-no-bg'><i class='fa fa-times orange-color'></i></button>\n" +
                "                </div>";
            carsCountInput.value = carCount;

            carContainer.appendChild(createElementFromHTML(content));

            document.getElementsByName('man-' + carCount)[0].dispatchEvent(new Event('change'));
        }


        function startModelChanged(element) {
            var i = element.getAttribute("data-i");


            var manInput = document.getElementsByName('man-' + i)[0];
            var brandInput = document.getElementsByName('brand-' + i)[0];
            var endModelInput = document.getElementsByName('end-model-' + i)[0];

            var lastModel = brands[manInput.selectedIndex][brandInput.selectedIndex].endModel;
            while (endModelInput.options.length) {
                endModelInput.remove(0);
            }


            for (var j = parseInt(element.options[element.selectedIndex].value); j <= lastModel; j++) {
                endModelInput.options.add(new Option(j, j));
                console.log(j);
            }

        }

        function removeCar(element) {
            var i = element.getAttribute('data-i');
            var container = document.getElementById("car-container-" + i);
            container.remove();
        }

        function addPicture() {
            picturesCount++;
            var newPicture = "<div id='file-input-container-" + picturesCount + "' class='input-group file-input'><input name='picture-" + picturesCount + "' type='file' /><i onclick='removePicture(this)' data-i='" + picturesCount + "' class='fa fa-times orange-color'></i>   </div>";
            picturesContainer.innerHTML += newPicture;
            picturesCountInput.value = picturesCount;
        }

        function removePicture(element) {
            var i = element.getAttribute('data-i');
            document.getElementById('file-input-container-' + i).remove();
        }


        function createElementFromHTML(htmlString) {
            var div = document.createElement('div');
            div.innerHTML = htmlString.trim();

            return div.firstChild;
        }

        var removedCars = [];

        function removePrevCar(element) {


            removedCars.push(
                {
                    'brand': element.getAttribute('data-brand'),
                    'startModel': element.getAttribute('data-startModel'),
                    'endModel': element.getAttribute('data-endModel')
                }
            );
            var removedCarsElement = document.getElementsByName('removedCars')[0];
            removedCarsElement.value = JSON.stringify(removedCars);
            element.parentElement.remove();
        }

        var removedPictures = [];

        function removePrevImage(element) {
            removedPictures.push(element.getAttribute('data-id'));
            console.log(element.getAttribute('data-id'));

            var removedPicture = document.getElementsByName('removedPictures')[0];
            removedPicture.value = JSON.stringify(removedPictures);
            element.parentElement.remove();
        }

    </script>

<?php
require '../templates/footer.php';
