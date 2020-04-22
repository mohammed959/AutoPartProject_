<?php
$title = 'بيانات البائع';
require $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once 'models/Seller.php';
require_once 'models/Review.php';
require_once 'models/Part.php';

echo "<script></script>";
$seller = Seller::byId($_GET['id']);
echo "<div class='container'>";
$rating = Review::bySeller($seller->id);
echo "
    <div id='seller-information'>
        <p class='name-head'>$seller->name</p>
        <div id='seller-rate-contener'>
            <p>التقييمات:</p>
            <div>
                ";
$numberOfRating = 0;
if (count($rating) > 0) {
    for ($i = 0; $i < count($rating); $i++) {
        $numberOfRating += $rating[$i]->rating;
    }
    $numberOfRating /= count($rating);
}
for ($i = 1; $i <= 5; $i++) {
    if ($i < $numberOfRating) {
        echo "<span class='fa fa-star checked'></span>";
    } else {
        echo "<span class='fa fa-star unchecked'></span>";
    }
}
echo "
                <p>من " . count($rating) . " مستخدم</p>
            </div>
        </div>
        <p id='location'>الموقع: " . $seller->location . "</p>
        <input type='button' value='تواصل مع البائع' onclick=''>
    </div>
    ";
$parts = Part::allBySeller($seller->id);
echo "
    <div id='my-parts'>
        <p class='name-head'>منتجات يبيعها البائع</p>
        <hr>
        <div id='seller-parts'>";

for ($i = 0; $i < count($parts); $i++) {
    if (count($parts[$i]->pictures) > 0) {
        echo "
            <div>
                <a href='/show_product.php?id=" . $parts[$i]->id . "'>
                <img class='' src='/files/" . $parts[$i]->pictures[0]->url . "' alt='الصورة غير متوفرة'></a>
                <p>" . $parts[$i]->name . "</p>
            </div>
            ";
    } else {
        echo "
                <div>
                    <a href='/show_product.php?id=" . $parts[$i]->id . "'>
                    <img src='/images/blank-image.png' alt='الصورة غير متوفرة'></a>
                    <p>" . $parts[$i]->name . "</p>
                </div>";
    }
}

echo "        
        </div>
    </div>
";

?>
</div>


</body>
</html>
