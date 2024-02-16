<?php
session_start();
include 'common/header.php';
include 'config.php';
include 'common/common/headerSingleProducts.php';
include 'common/common/bodySingleProduct.php';

$search = ""; // Replace with your search query
$maxProducts = 3;

$sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url
        FROM products p
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN product_images pi ON p.id = pi.product_id
        WHERE p.active != 2 AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%')
        ORDER BY sort_order";
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
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($con, $_GET['search']);
    $searchSql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url
                  FROM products p
                  LEFT JOIN brands b ON p.brand_id = b.id
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN product_images pi ON p.id = pi.product_id
                  WHERE p.active != 2 AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%')
                  ORDER BY sort_order";
    $searchResult = mysqli_query($con, $searchSql);
    $searchResults = mysqli_fetch_all($searchResult, MYSQLI_ASSOC);

    if (!empty($searchResults)) {
        $firstProduct = $searchResults[0];
        $productId = $firstProduct['id'];
?>
        <script>
            // Modify the URL using JavaScript and reload the page
            var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?id=<?php echo $productId; ?>";
            window.location.replace(newUrl);
        </script>
<?php
    } else {
        echo "<script>alert('Không thấy Món'); window.history.back();</script>";
        exit(); // Stop execution
    }
}

if (isset($_POST['submit_review'])) {
    // Get the values from the form
    $reviewerName = mysqli_real_escape_string($con, $_POST['name']);
    $reviewerEmail = mysqli_real_escape_string($con, $_POST['email']);
    $productName = mysqli_real_escape_string($con, $_POST['productname']);
    $reviewText = mysqli_real_escape_string($con, $_POST['review']);
    $rating = mysqli_real_escape_string($con, $_POST['rating']);

    // Insert the review into the database
    $insertReviewSql = "INSERT INTO reviews (name, email, productname, review, rating)
                        VALUES ('$reviewerName', '$reviewerEmail', '$productName', '$reviewText', '$rating')";

    $insertReviewResult = mysqli_query($con, $insertReviewSql);

    // Check if the insertion was successful
    if ($insertReviewResult) {
        echo "<script>alert('Cảm ơn bạn vì đã góp ý'); window.history.back();</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}


?>

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
            <div class="single-sidebar">
                <h2 class="sidebar-title">Tìm kiếm sản phẩm</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" id="searchForm">
                    <input type="text" name="search" id="searchInput" placeholder="Tìm Kiếm...">
                    <input type="submit" value="Tìm">
                </form>
            </div>

                <?php
                    $counter = 0;
                    foreach ($products as $product) {
                        if ($counter >= $maxProducts) {
                            break;
                        }
                        ?>
                        <div class="thubmnail-recent">
                            <img src="img/<?php echo $product['image_url']; ?>" class="recent-thumb" alt="">
                            <h2><a href="single-product.php?id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></h2>
                            <div class="product-sidebar-price">
                                <ins><?php echo $product['price']; ?>đ</ins> <del><?php echo $product['old_price']; ?>đ</del>
                            </div>
                        </div>
                        <?php
                        $counter++;
                    }
                    ?>

                <div class="single-sidebar">
                    <h2 class="sidebar-title">Sản phẩm</h2>
                    <ul>
                        <li><a href="shop.php">CÁ HOA KỲ</a></li>
                        <li><a href="shop.php">Tôm Nhật Bản</a></li>
                        <li><a href="shop.php">Cua Canada</a></li>
                        <li><a href="shop.php">Mực Hàn Quốc</a></li>
                        <li><a href="shop.php">Cua Hoàng Đế</a></li>
                    </ul>
                </div>
            </div>

            <?php
            if (isset($_GET['id'])) {
                $productId = $_GET['id'];

                $productSql = "SELECT p.*, b.name AS brand_name, c.name AS category_name, pi.image_url
                               FROM products p
                               LEFT JOIN brands b ON p.brand_id = b.id
                               LEFT JOIN categories c ON p.category_id = c.id
                               LEFT JOIN product_images pi ON p.id = pi.product_id
                               WHERE p.active != 2 AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%')
                               AND p.id = $productId";

                $productResult = mysqli_query($con, $productSql);

                if ($productResult) {
                    $product = mysqli_fetch_assoc($productResult);

                    if ($product) {
                        $image = $product['image_url'];
                        $name = $product['name'];
                        $price = $product['price'];
                        $oldPrice = $product['old_price'];
                        $description = $product['description'];
                        $brand = $product['brand_name'];
                        $category = $product['category_name'];
                        $tagNames = !empty($product['tag_names']) ? explode(',', $product['tag_names']) : [];

                        if (isset($product['category_name'])) {
                            $currentCategory = $product['category_name'];

                            $relatedSql = "SELECT p.*, b.name AS brand_name, c.name AS category_name, pi.image_url
                                           FROM products p
                                           LEFT JOIN brands b ON p.brand_id = b.id
                                           LEFT JOIN categories c ON p.category_id = c.id
                                           LEFT JOIN product_images pi ON p.id = pi.product_id
                                           WHERE p.active != 2 AND c.name = '$currentCategory' AND p.id != $productId
                                           ORDER BY RAND() LIMIT $maxProducts";

                            $relatedResult = mysqli_query($con, $relatedSql);
                            $relatedProducts = mysqli_fetch_all($relatedResult, MYSQLI_ASSOC);
                        }

                        // HTML output

                        ?>
                        <div class="col-md-8">
                            <div class="product-content-right">
                                <div class="product-breadcroumb">
                                <a href="index.php">Trang chủ</a>
                                <?php if (!empty($category)): ?>
                                    <a href="shop.php"><?php echo $category; ?></a>
                                <?php endif; ?>
                                <?php if (!empty($name)): ?>
                                    <a href=""><?php echo $name; ?></a>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="product-images">
                                        <div class="product-main-img">
                                            <img src="img/<?php echo $image; ?>" alt="<?php echo $name; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="product-inner">
                                        <h2 class="product-name"><?php echo $name; ?></h2>
                                        <div class="product-inner-price">
                                            <ins><?php echo $price; ?>đ</ins>
                                            <?php if ($oldPrice): ?>
                                                <del><?php echo $oldPrice; ?>đ</del>
                                            <?php endif; ?>
                                        </div>

                                        <form action="" class="cart">
                                            <div class="quantity">
                                                <input type="number" size="4" class="input-text qty text" title="Qty" value="1" name="quantity" min="1" step="1" onclick="addToCart(<?php echo $id; ?>); return false;">
                                            </div>
                                            <button class="add_to_cart_button" type="submit" onclick="addToCart(<?php echo $productId; ?>); return false;">Thêm vào giỏ hàng</button>
                                        </form>

                                        <div class="product-inner-category">
                                            <p>Danh mục: <a href="ca.php"><?php echo $category; ?></a>. Tags:
                                                <?php if (!empty($product['tags'])): ?>
                                                    <?php $tags = explode(',', $product['tags']); ?>
                                                    <?php foreach ($tags as $tag): ?>
                                                        <a href="ca.php"><?php echo $tag; ?></a>,
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </p>
                                        </div>

                                        <div role="tabpanel">
                                            <ul class="product-tab" role="tablist">
                                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Chi tiết</a></li>
                                                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Reviews</a></li>
                                            </ul>

                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="home">
                                                    <h2>Chi tiết sản phẩm</h2>
                                                    <p><?php echo $description; ?></p>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="profile">
                                                    <h2>Reviews</h2>
                                                    <div class="submit-review">
                                                        <?php
                                                        // Retrieve user information from the session
                                                        $userId = $_SESSION['user_id'];
                                                        $conn = mysqli_connect("localhost", "root", "", "sell_phones");
                                                        $userSql = "SELECT * FROM users WHERE id = $userId";
                                                        $userResult = mysqli_query($conn, $userSql);

                                                        if ($userResult) {
                                                            $user = mysqli_fetch_assoc($userResult);
                                                            $userName = $user['username'];
                                                            $userEmail = $user['email'];
                                                        }
                                                        ?>

                                                        <form method="post" action="">
                                                            <p><label for="name">Tên</label> <input name="name" type="text" value="<?php echo $userName; ?>" readonly></p>
                                                            <p><label for="email">Email</label> <input name="email" type="email" value="<?php echo $userEmail; ?>" readonly></p>

                                                            <div class="rating-chooser">
                                                                <p>Đánh giá</p>
                                                                <select name="rating" id="rating">
                                                                    <option value="1">1 Sao</option>
                                                                    <option value="2">2 Sao</option>
                                                                    <option value="3">3 Sao</option>
                                                                    <option value="4">4 Sao</option>
                                                                    <option value="5">5 Sao</option>
                                                                </select>
                                                            </div>

                                                            <p><label for="review">Đánh giá của bạn</label> <textarea name="review" id="review" cols="30" rows="10"></textarea></p>
                                                            <input type="hidden" name="productname" value="<?php echo $name; ?>">
                                                            <p><input type="submit" name="submit_review" value="Gửi"></p>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo "<div class='container'><p>Product not found.</p></div>";
                    }
                } else {
                    echo "Query Error: " . mysqli_error($con);
                }
            } else {
                echo "<div class='container'><p>Product ID not provided.</p></div>";
            }
            ?>

                            <div class="related-products-wrapper">
                                <h2 class="related-products-title">Sản Phẩm Liên Quan</h2>
                                <div class="related-products-carousel">
                                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                                        <div class="single-product">
                                            <div class="product-f-image">
                                                <img src="img/<?php echo $relatedProduct['image_url']; ?>" alt="">
                                                <div class="product-hover">
                                                    <a href="cart.php" class="add-to-cart-link" onclick="addToCart(<?php echo $relatedProduct['id']; ?>); return false;">
                                                        <i class="fa fa-shopping-cart"></i> Add to cart
                                                    </a>
                                                    <a href="single-product.php?id=<?php echo $relatedProduct['id']; ?>" class="view-details-link">
                                                        <i class="fa fa-link"></i> See details
                                                    </a>
                                                </div>
                                            </div>
                                            <h2><a href="single-product.php?id=<?php echo $relatedProduct['id']; ?>"><?php echo $relatedProduct['name']; ?></a></h2>
                                            <div class="product-carousel-price">
                                                <ins><?php echo $relatedProduct['price']; ?>đ</ins>
                                                <?php if ($relatedProduct['old_price']): ?>
                                                    <del><?php echo $relatedProduct['old_price']; ?>đ</del>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="footer-top-area">
            <div class="zigzag-bottom"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="footer-about-us">
                            <h2>u<span>Stora</span></h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis sunt id doloribus
                                vero quam laborum quas alias dolores blanditiis iusto consequatur, modi aliquid eveniet
                                eligendi iure eaque ipsam iste, pariatur omnis sint! Suscipit, debitis, quisquam.
                                Laborum commodi veritatis magni at?</p>
                            <div class="footer-social">
                                <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
                                <a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
                                <a href="#" target="_blank"><i class="fa fa-youtube"></i></a>
                                <a href="#" target="_blank"><i class="fa fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="footer-menu">
                            <h2 class="footer-wid-title">User Navigation </h2>
                            <ul>
                                <li><a href="">My account</a></li>
                                <li><a href="">Order history</a></li>
                                <li><a href="">Wishlist</a></li>
                                <li><a href="">Vendor contact</a></li>
                                <li><a href="">Front page</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="footer-menu">
                            <h2 class="footer-wid-title">Categories</h2>
                            <ul>
                                <li><a href="">Mobile Phone</a></li>
                                <li><a href="">Home accesseries</a></li>
                                <li><a href="">LED TV</a></li>
                                <li><a href="">Computer</a></li>
                                <li><a href="">Gadets</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="footer-newsletter">
                            <h2 class="footer-wid-title">Newsletter</h2>
                            <p>Sign up to our newsletter and get exclusive deals you wont find anywhere else straight to
                                your inbox!</p>
                            <div class="newsletter-form">
                                <input type="email" placeholder="Type your email">
                                <input type="submit" value="Subscribe">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="copyright">
                            <p>&copy; 2015 uCommerce. All Rights Reserved. <a href="http://www.freshdesignweb.com" target="_blank">freshDesignweb.com</a></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="footer-card-icon">
                            <i class="fa fa-cc-discover"></i>
                            <i class="fa fa-cc-mastercard"></i>
                            <i class="fa fa-cc-paypal"></i>
                            <i class="fa fa-cc-visa"></i>
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

        <!-- Latest jQuery form server -->
        <script src="https://code.jquery.com/jquery.min.js"></script>

        <!-- Bootstrap JS form CDN -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

        <!-- jQuery sticky menu -->
        <script src="js/owl.carousel.min.js"></script>
        <script src="js/jquery.sticky.js"></script>

        <!-- jQuery easing -->
        <script src="js/jquery.easing.1.3.min.js"></script>

        <!-- Main Script -->
        <script src="js/main.js"></script>
    </body>
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

