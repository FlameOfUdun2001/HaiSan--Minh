<?php
session_start();
include 'common/header.php';
include 'config.php';

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sell_fishs";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the category ID based on the page number
$page = isset($_GET['page']) ? $_GET['page'] : 1;
if ($page == 1) {
    $category_id = 2;
} elseif ($page == 2) {
    $category_id = 3;
} elseif($page == 3) {
    $category_id = 4;
}


// Define the number of items per page and calculate the offset
$itemsPerPage = 8;
$offset = ($page - 1) * $itemsPerPage;

$sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url
        FROM products p
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN product_images pi ON p.id = pi.product_id
        WHERE p.active != 2
            AND p.category_id = $category_id
        ORDER BY p.sort_order
        LIMIT $itemsPerPage OFFSET $offset";

$result = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Calculate the total number of pages based on the category
$totalPages = ceil(count($products) / $itemsPerPage);

// Determine the current page (for pagination)
$currentPage = $page;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Category: <?php echo "Category $category_id"; ?></title>
</head>
<body>

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
                        <ins><?php echo $price; ?></ins>
                        <?php if ($oldPrice) : ?>
                            <del><?php echo $oldPrice; ?></del>
                        <?php endif; ?>
                    </div>

                    <div class="product-option-shop">
                    <a class="add_to_cart_button" onclick="addToCart(<?php echo $product['id']; ?>); return false;" data-quantity="1" data-product_id="<?php echo $product['id']; ?>" rel="nofollow">Add to cart</a>
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
                                    <a href="category.php?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li <?php if ($i == $currentPage) echo 'class="active"'; ?>>
                                    <a href="category.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <?php if ($currentPage < $totalPages) : ?>
                                <li>
                                    <a href="category.php?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
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

<?php
// Close the database connection
$conn->close();
?>

</body>
</html>