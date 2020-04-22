<?php

require_once 'models/Part.php';
require_once 'models/PartPicture.php';
require_once 'models/Category.php';
require_once 'models/Car.php';
require_once 'models/Seller.php';
require_once 'models/Review.php';
require_once 'models/User.php';
require_once 'utils/constants.php';

$id = $_GET['id'];
$part = Part::byId($id);
$pictures = PartPicture::byPart($part->id);

$seller = $part->seller();

$title = $part->name;
require 'templates/header.php';

echo "<div class='container'> <p class='name-head'>$part->name</p>
        <div class='contener'>
            <div id='contener-images'>";

if (count($pictures) > 0) {
    echo "<img id='main-image' src='/images/" . $part->pictures[0]->url . "' alt='الصورة غير متوفرة'>
    <div id='other-images'>";
    for ($i = 1; $i < count($part->pictures); $i++) {
        echo "<img class='child-image' src='/images/" . $part->pictures[$i]->url . "' alt='الصورة غير متوفرة'>";
    }
    echo "</div>";
} else {
    echo "<img id='main-image' src='/images/blank-image.png' alt='الصورة غير متوفرة'>";
}
//onclick='window.open(this.src)'

echo "                
            </div>
            <div id='rate-contener'>
                <div>";
$numberOfRating = 0;
$rating = Review::byPart($part->id);
for ($i = 0; $i < count($rating); $i++) {
    $numberOfRating += $rating->rating;
}
if (count($rating) > 0)
    $numberOfRating /= count($rating);
for ($i = 0; $i < $numberOfRating; $i++) {
    echo "<span class='fa fa-star checked'></span>";
}
for ($i = 5; $i > $numberOfRating; $i--) {
    echo "<span class='fa fa-star unchecked'></span>";
}
echo "
                    <p>من قبل " . Review::numberOfReviews($part->id) . " شخص</p>
                </div>
            </div>
            <div id='contener-information'>
                <div>
                    <p id='price'>السعر: $part->price</p>
                    <p id='seller-name'>اسم البائع: $seller->name</p>
                </div>
            </div>
        </div>";
/*echo "
        <img id='main-image' src='$pictures[0]->$url' alt='الصورة غير متوفرة'>

        <div id='other-images'>
        ";
foreach ($pictures as $picture) {
    echo "<img class='' src='$picture->$url' alt='الصورة غير متوفرة'>";
}
echo "</div>";*/
//} else {
//
//}


echo "
<div class='describe-contener'>
    <p class='name-head'>وصف المنتج</p>
    <hr>
    <p class='describe'>$part->description</p>
    <hr>
</div>

<div class='describe-contener'>
    <p class='name-head'>منتجات مشابهة</p>
    <hr>
    <div class='describe'>
        <div class='similar-products-images'>
        ";

$parts = Part::byCategories([$part->category], 1, 7);
for ($i = 0; $i < count($parts) && $i < 6; $i++) {
    if ($parts[$i]->id != $part->id) {
        if (count($parts[$i]->pictures) > 0) {
            echo "
                <div>
                    <img src='/files/parts/" . $parts[$i]->pictures[0] . "' alt='الصورة غير متوفرة'>
                    <p>" . $parts[$i]->name . "</p>
                </div>";
        } else {
            echo "
                <div>
                    <img src='/images/blank-image.png' alt='الصورة غير متوفرة'>
                    <p>" . $parts[$i]->name . "</p>
                </div>";
        }
    }
}
echo "
        </div>
    </div>
    <hr>
</div>
<div>
    <p class='name-head'>التقييمات</p>
    <div class='contener'>
        <div id='previous-replies'>
            ";
if (count($rating) > 0) {
    for ($i = 0; $i < count($rating); $i++) {
        $userTemp = User::byId($rating[$i]->userId);
        echo "
                <div>
                    <img class='person-image' src='/images/blank_preview.png' alt='الصورة غير متوفرة'>
                </div>
                <div>
                    <div>
                        <p class='person-name'>" . $userTemp->name . "</p>";
        for ($i = 1; $i <= 5; $i++) {
            if ($i < $rating->rating) {
                echo "<span class='fa fa-star checked'></span>";
            } else {
                echo "<span class='fa fa-star unchecked'></span>";
            }
        }

        echo "
                    </div>
                    <p>" . $rating->comment . "</p>
            ";
    }
} else {
    echo "<p>لا يوجد تعليقات </p>";
}

echo "
        </div>";

if (isset($_SESSION['id']) && $_SESSION['user_type'] == USER_TYPE_USER) {
    echo "
        <div id='new-reply'>
            <form class='reply' method='post'>
                <textarea id='reply-text' cols='50' rows='5'></textarea>
                <div class='rate'>
                    <input type='radio' id='star5' name='rate' value='5' />
                    <label for='star5' title='5'>5 stars</label>
                    <input type='radio' id='star4' name='rate' value='4' />
                    <label for='star4' title='4'>4 stars</label>
                    <input type='radio' id='star3' name='rate' value='3' />
                    <label for='star3' title='3'>3 stars</label>
                    <input type='radio' id='star2' name='rate' value='2' />
                    <label for='star2' title='2'>2 stars</label>
                    <input type='radio' id='star1' name='rate' value='1' />
                    <label for='star1' title='1'>1 star</label>
                </div>
                <input id='add-reply' type='submit' onclick='' value='أضف تعليق'>
            </form>
        </div>";
}
echo "
    </div>
</div>
</div>
<script src='js/show_product_js.js'></script>
</body>
</html>";
?>

