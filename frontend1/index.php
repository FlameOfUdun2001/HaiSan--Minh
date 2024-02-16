<?php
session_start();
include 'common/header.php';
include 'config.php';
include 'common/slider.php';
include 'common/promo.php';

$search = '';
$sql = "SELECT p.id, p.*, b.name AS brand_name, c.name AS category_name, pi.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.active != 2 AND (p.name LIKE '%{$search}%' OR p.description LIKE '%{$search}%') ORDER BY sort_order";

$result = mysqli_query($con, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_POST['add_to_cart'])) {
    // Check if the product is already in the cart
    $product_id = $_POST['add_to_cart'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Add the product to the cart
        $product = $products[$product_id];
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1,
            'image_url' => $product['image_url'], // Store the product image URL in the session
        ];
    }
}

?>

<div class="maincontent-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="latest-product">
                    <h2 class="section-title">Hải Sản Mới Nhất</h2>
                    <div class="product-carousel">

                        <?php
                        foreach ($products as $product) {
                            $id = $product['id'];
                            $image = $product['image_url'];
                            $name = $product['name'];
                            $price = $product['price'];
                            $oldPrice = $product['old_price'];
                        ?>

                            <div class="single-product">
                                <div class="product-f-image">
                                    <img src='img/<?php echo $product['image_url']; ?>' alt="">
                                    <div class="product-hover">
                                    <a href="#" class="add-to-cart-link" onclick="addToCart(<?php echo $id; ?>); return false;">
                                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                                    </a>
                                        <a href="single-product.php?id=<?php echo $product['id']; ?>" class="view-details-link">
                                            <i class="fa fa-link"></i> Xem chi tiết
                                        </a>

                                    </div>
                                </div>

                                <h2><a href="single-product.php"><?php echo $name; ?></a></h2>

                                <div class="product-carousel-price">
                                    <ins><?php echo number_format($price); ?> đ</ins>
                                    <?php if ($oldPrice) : ?>
                                        <del><?php echo number_format($oldPrice); ?> đ</del>
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

<script>
    function addToCart(productId) {
        // Create a hidden form
        var form = document.createElement('form');
        form.method = 'post';
        form.action = 'cart.php';

        // Create a hidden input field for the product_id
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'add_to_cart';
        input.value = productId;

        // Append the input field to the form
        form.appendChild(input);

        // Append the form to the document and submit it
        document.body.appendChild(form);
        form.submit();
    }
</script>
<script>
    function addToCart(productId) {
        // Create a hidden form
        var form = document.createElement('form');
        form.method = 'post';
        form.action = 'cart.php';

        // Create a hidden input field for the product_id
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'add_to_cart';
        input.value = productId;

        // Append the input field to the form
        form.appendChild(input);

        // Append the form to the document and submit it
        document.body.appendChild(form);
        form.submit();
    }
</script>