<?php
include 'common/header.php';
include 'common/shopbase.php';
include 'config.php';

$search = '';
$sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.active != 2 AND (p.name LIKE '%{$search}%' OR p.description LIKE '%{$search}%') ORDER BY sort_order";
$result = mysqli_query($con, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <?php foreach ($products as $product) {
                $image = $product['image_url'];
                $name = $product['name'];
                $price = $product['price'];
                $oldPrice = $product['old_price'];
            ?>
                <div class="col-md-3 col-sm-6">
                    <div class="single-shop-product">
                        <div class="product-upper">
                            <img src='img/<?php echo $product['image_url']; ?>' alt="">
                        </div>
                        <h2><a href=""><?php echo $name; ?></a></h2>
                        <div class="product-carousel-price">
                            <ins><?php echo $price; ?></ins>
                            <?php if ($oldPrice) : ?>
                                <del><?php echo $oldPrice; ?></del>
                            <?php endif; ?>
                        </div>

                        <div class="product-option-shop">
                            <a class="add_to_cart_button" data-quantity="1" data-product_sku="" data-product_id="70" rel="nofollow" href="/canvas/shop/?add-to-cart=70">Add to cart</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="product-pagination text-center">
                    <nav>
                        <ul class="pagination">
                            <li>
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li>
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'common/footer.php';
?>