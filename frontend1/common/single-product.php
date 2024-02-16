<?php
include 'common/header.php';
include 'config.php';
include 'common/bodySingleProducts.php';
?>
<div class="row">
    <div class="col-md-4">
        <div class="single-sidebar">
            <h2 class="sidebar-title">Search Products</h2>
            <form action="">
                <input type="text" placeholder="Search products...">
                <input type="submit" value="Search">
            </form>
        </div>

        <?php
        $search = ""; // Replace with your search query
        $maxProducts = 3;

        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.active != 2 AND (p.name LIKE '%{$search}%' OR p.description LIKE '%{$search}%') ORDER BY sort_order";

        $result = mysqli_query($con, $sql);
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // Loop through the products array and display each product
        $counter = 0;
        foreach ($products as $product) {
            if ($counter >= $maxProducts) {
                break;
            }
            ?>
            <div class="thubmnail-recent">
                <img src="img/<?php echo $product['image_url']; ?>" class="recent-thumb" alt="">
                <h2><a href=""><?php echo $product['name']; ?></a></h2>
                <div class="product-sidebar-price">
                    <ins>$<?php echo $product['price']; ?></ins> <del>$<?php echo $product['old_price']; ?></del>
                </div>
            </div>
            <?php
            $counter++;
        }

        // Close the database connection
        mysqli_close($con);
        ?>

    <div class="col-md-8">
        <?php
        $productID = $_GET['id'];

        // Perform a database query to fetch the product details
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.id = $productID";
        $result = mysqli_query($con, $sql);
        $product = mysqli_fetch_assoc($result);
        ?>

        <!-- HTML structure to display product details -->
        <div class="product-content-right">
            <div class="product-breadcroumb">
                <a href="">Home</a>
                <a href=""><?php echo $product['category_name']; ?></a>
                <a href=""><?php echo $product['name']; ?></a>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="product-images">
                        <div class="product-main-img">
                            <img src="<?php echo $product['image_url']; ?>" alt="">
                        </div>

                        <div class="product-gallery">
                            <!-- Add additional product images here if available -->
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="product-inner">
                        <h2 class="product-name"><?php echo $product['name']; ?></h2>
                        <div class="product-inner-price">
                            <ins>$<?php echo $product['price']; ?></ins>
                            <?php if ($product['old_price']) : ?>
                                <del>$<?php echo $product['old_price']; ?></del>
                            <?php endif; ?>
                        </div>

                        <form action="" class="cart">
                            <div class="quantity">
                                <input type="number" size="4" class="input-text qty text" title="Qty" value="1" name="quantity" min="1" step="1">
                            </div>
                            <button class="add_to_cart_button" type="submit">Add to cart</button>
                        </form>

                        <div class="product-inner-category">
                            <p>Category: <a href=""><?php echo $product['category_name']; ?></a>. Tags: <a href="">awesome</a>, <a href="">best</a>, <a href="">sale</a>, <a href="">shoes</a>. </p>
                        </div>

                        <div role="tabpanel">
                            <ul class="product-tab" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Description</a></li>
                                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Reviews</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="home">
                                    <h2>Product Description</h2>
                                    <p><?php echo $product['description']; ?></p>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="profile">
                                    <h2>Reviews</h2>
                                    <div class="submit-review">
                                        <p><label for="name">Name</label> <input name="name" type="text"></p>
                                        <p><label for="email">Email</label> <input name="email" type="email"></p>
                                        <div class="rating-chooser">
                                            <p>Your rating</p>

                                            <div class="rating-wrap-post">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                        </div>
                                        <p><label for="review">Your review</label> <textarea name="review" id="" cols="30" rows="10"></textarea></p>
                                        <p><input type="submit" value="Submit"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="single-sidebar">
            <h2 class="sidebar-title">Recent Posts</h2>
            <ul>
                <li><a href="">Sony Smart TV - 2015</a></li>
                <li><a href="">Sony Smart TV - 2015</a></li>
                <li><a href="">Sony Smart TV - 2015</a></li>
                <li><a href="">Sony Smart TV - 2015</a></li>
                <li><a href="">Sony Smart TV - 2015</a></li>
            </ul>
        </div>
    </div>
                            <div class="related-products-wrapper">
                                <h2 class="related-products-title">Related Products</h2>
                                <div class="related-products-carousel">
                                    <div class="single-product">
                                        <div class="product-f-image">
                                            <img src="img/product-1.jpg" alt="">
                                            <div class="product-hover">
                                                <a href="" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i>
                                                    Add to cart</a>
                                                <a href="" class="view-details-link"><i class="fa fa-link"></i> See
                                                    details</a>
                                            </div>
                                        </div>

                                        <h2><a href="">Sony Smart TV - 2015</a></h2>

                                        <div class="product-carousel-price">
                                            <ins>$700.00</ins> <del>$100.00</del>
                                        </div>
                                    </div>
                                    <div class="single-product">
                                        <div class="product-f-image">
                                            <img src="img/product-2.jpg" alt="">
                                            <div class="product-hover">
                                                <a href="" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i>
                                                    Add to cart</a>
                                                <a href="" class="view-details-link"><i class="fa fa-link"></i> See
                                                    details</a>
                                            </div>
                                        </div>

                                        <h2><a href="">Apple new mac book 2015 March :P</a></h2>
                                        <div class="product-carousel-price">
                                            <ins>$899.00</ins> <del>$999.00</del>
                                        </div>
                                    </div>
                                    <div class="single-product">
                                        <div class="product-f-image">
                                            <img src="img/product-3.jpg" alt="">
                                            <div class="product-hover">
                                                <a href="" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i>
                                                    Add to cart</a>
                                                <a href="" class="view-details-link"><i class="fa fa-link"></i> See
                                                    details</a>
                                            </div>
                                        </div>

                                        <h2><a href="">Apple new i phone 6</a></h2>

                                        <div class="product-carousel-price">
                                            <ins>$400.00</ins> <del>$425.00</del>
                                        </div>
                                    </div>
                                    <div class="single-product">
                                        <div class="product-f-image">
                                            <img src="img/product-4.jpg" alt="">
                                            <div class="product-hover">
                                                <a href="" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i>
                                                    Add to cart</a>
                                                <a href="" class="view-details-link"><i class="fa fa-link"></i> See
                                                    details</a>
                                            </div>
                                        </div>

                                        <h2><a href="">Sony playstation microsoft</a></h2>

                                        <div class="product-carousel-price">
                                            <ins>$200.00</ins> <del>$225.00</del>
                                        </div>
                                    </div>
                                    <div class="single-product">
                                        <div class="product-f-image">
                                            <img src="img/product-5.jpg" alt="">
                                            <div class="product-hover">
                                                <a href="" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i>
                                                    Add to cart</a>
                                                <a href="" class="view-details-link"><i class="fa fa-link"></i> See
                                                    details</a>
                                            </div>
                                        </div>

                                        <h2><a href="">Sony Smart Air Condtion</a></h2>

                                        <div class="product-carousel-price">
                                            <ins>$1200.00</ins> <del>$1355.00</del>
                                        </div>
                                    </div>
                                    <div class="single-product">
                                        <div class="product-f-image">
                                            <img src="img/product-6.jpg" alt="">
                                            <div class="product-hover">
                                                <a href="" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i>
                                                    Add to cart</a>
                                                <a href="" class="view-details-link"><i class="fa fa-link"></i> See
                                                    details</a>
                                            </div>
                                        </div>

                                        <h2><a href="">Samsung gallaxy note 4</a></h2>

                                        <div class="product-carousel-price">
                                            <ins>$400.00</ins>
                                        </div>
                                    </div>
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
</php>