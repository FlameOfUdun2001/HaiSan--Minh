<?php
session_start();
include 'common/header.php';
include 'common/shopbase.php';
include 'config.php';

// Max products per page will be 8 if not go to another page
$search = '';
$countSql = "SELECT COUNT(*) FROM products p WHERE p.active != 2 AND (p.name LIKE '%{$search}%' OR p.description LIKE '%{$search}%')";
$countResult = mysqli_query($con, $countSql);
$countRow = mysqli_fetch_row($countResult);
$totalProducts = $countRow[0];
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = 8;
$start = ($currentPage - 1) * $perPage;

$sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.active != 2 AND (p.name LIKE '%{$search}%' OR p.description LIKE '%{$search}%') ORDER BY sort_order LIMIT $start, $perPage";
$result = mysqli_query($con, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
$totalPages = ceil($totalProducts / $perPage);

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
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <?php
        $counter = 0;
        $productCount = count($products);
        foreach ($products as $product) {
            $image = $product['image_url'];
            $name = $product['name'];
            $price = $product['price'];
            $oldPrice = $product['old_price'];
            $imagePath = 'img/' . $image;

            if ($counter % 4 === 0) {
                echo '<div class="row">';
            }
        ?>
            <div class="col-md-3 col-sm-6">
                <div class="single-shop-product">
                    <div class="product-upper">
                        <img src="<?php echo $imagePath; ?>" alt="">
                    </div>
                    <h2><a href=""><?php echo $name; ?></a></h2>
                    <div class="product-carousel-price">
                        <ins><?php echo number_format($price); ?> đ</ins>
                        <?php if ($oldPrice) : ?>
                            <del><?php echo number_format($oldPrice); ?> đ</del>
                        <?php endif; ?>
                    </div>

                    <div class="product-option-shop">
                    <a class="add_to_cart_button" data-quantity="1" data-product_sku="" data-product_id="<?php echo $product['id']; ?>" rel="nofollow" onclick="addToCart(<?php echo $product['id']; ?>); return false;" href="#">Thêm vào giỏ</a>
                    </div>
                </div>
            </div>
        <?php
            $counter++;
            if ($counter % 4 === 0 || $counter === $productCount) {
                echo '</div>'; // Close the row after four products or if it's the last product
            }
        }
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="product-pagination text-center">
                    <nav>
                        <ul class="pagination">
                            <?php if ($currentPage > 1) : ?>
                                <li>
                                    <a href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li <?php if ($i == $currentPage) echo 'class="active"' ?>>
                                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <?php if ($currentPage < $totalPages) : ?>
                                <li>
                                    <a href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

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
<?php
include 'common/footer.php';
?>