<?php
include 'config.php';
$sql = "SELECT * FROM brands WHERE active != 2 ORDER BY sort_order";
$result = mysqli_query($con, $sql);
$brands = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="brands-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="brand-wrapper">
                    <div class="brand-list">
                        <?php
                        // Loop through the brands and generate image tags
                        foreach ($brands as $brand) {
                            echo '<img src="' . $brand["image_url"] . '" alt="">';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End brands area -->