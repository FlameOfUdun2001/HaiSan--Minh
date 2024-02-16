<?php
include 'config.php';
$sql = "SELECT * FROM banners WHERE active != 2 ORDER BY sort_order";
$result = mysqli_query($con, $sql);
$banners = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<div class="slider-area">
    <div class="block-slider block-slider4">
        <ul class="" id="bxslider-home4">
            <?php foreach ($banners as $banner) {
                echo '<li>';
                echo '<img src="' . $banner['image_url'] . '" alt="Slide">';
                echo '<div class="caption-group">';
                echo '<h2 class="caption title">' . $banner['title'] . '</h2>';
                echo '<h4 class="caption subtitle">' . $banner['content'] . '</h4>';
                echo '<a class="caption button-radius" href="shop.php"><span class="icon"></span>Mua Ngay</a>';
                echo '</div>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</div>