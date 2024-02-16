<?php
include 'common/header.php';
include 'config.php';

$search = '';
$category_id = 5; // Set the desired category ID
$sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.active != 2 AND p.category_id = $category_id AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%') ORDER BY sort_order";
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
<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Mực</h2>
                </div>
            </div>
        </div>
    </div>
</div>
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
                        <h2><a href="single-product.php?id=<?php echo $product['id']; ?>"><?php echo $name; ?></a></h2>
                        <div class="product-carousel-price">
                            <ins><?php echo number_format($price); ?></ins>
                            <?php if ($oldPrice) : ?>
                                <del><?php echo number_format($oldPrice); ?></del>
                            <?php endif; ?>
                        </div>

                        <div class="product-option-shop">
                        <a class="add_to_cart_button" data-quantity="1" data-product_sku="" data-product_id="<?php echo $product['id']; ?>" rel="nofollow" onclick="addToCart(<?php echo $product['id']; ?>); return false;" href="#">Thêm vào giỏ</a>
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