<?php
if (!isset($_SESSION)) {
    session_start();
}
include 'common/header.php';
include 'config.php';
include 'common/slider.php';
include 'common/promo.php';

$search = '';
$sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.active != 2 AND (p.name LIKE '%{$search}%' OR p.description LIKE '%{$search}%') ORDER BY sort_order";
$result = mysqli_query($con, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<div class="maincontent-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="latest-product">
                    <h2 class="section-title">Latest Products</h2>
                    <div class="product-carousel">

                        <?php
                        foreach ($products as $product) {
                            $image = $product['image_url'];
                            $name = $product['name'];
                            $price = $product['price'];
                            $oldPrice = $product['old_price'];
                        ?>

                            <div class="single-product">
                                <div class="product-f-image">
                                <img src='img/<?php echo $product['image_url']; ?>' alt="">
                                    <div class="product-hover">
                                        <a href="cart.php" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                        <a href="single-product.php?id=<?php echo $product['id']; ?>" class="view-details-link"><i class="fa fa-link"></i> See details</a>
                                    </div>
                                </div>

                                <h2><a href="single-product.php"><?php echo $name; ?></a></h2>

                                <div class="product-carousel-price">
                                    <ins>$<?php echo $price; ?></ins>
                                    <?php if ($oldPrice) : ?>
                                        <del>$<?php echo $oldPrice; ?></del>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div> <!-- End main content area -->
<?php include 'common/brandindex.php'; ?>
<?php include 'common/seller.php'; ?>
<?php include 'common/footer.php'; ?>
