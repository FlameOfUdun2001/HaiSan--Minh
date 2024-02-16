
<div class="product-widget-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
        <div class="col-md-4">
    <div class="single-product-widget">
        <h2 class="product-wid-title">Bán Chạy</h2>
        <a href="shop.php" class="wid-view-more">Xem tất</a>

        <?php
        include 'config.php';

        // Fetch products with is_best_sell = 1
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.active != 2 AND p.is_best_sell = 1 ORDER BY p.sort_order";
        $result = mysqli_query($con, $sql);
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $counter = 0; // Counter variable to limit the number of products

        foreach ($products as $product) {
            if ($counter >= 3) { // Break the loop after three iterations
                break;
            }
            $counter++;
        ?>
            <div class="single-wid-product">
                <a href="single-product.php?id=<?php echo $product['id']; ?>"><img src='img/<?php echo $product['image_url']; ?>' alt='' class="product-thumb"></a>
                <h2><a href="single-product.php?id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></h2>
                <div class="product-wid-price">
                    <ins><?php echo number_format($product['price']); ?>đ</ins> <del><?php echo number_format($product['old_price']); ?>đ</del>
                </div>
            </div>
        <?php
        }
        mysqli_close($con);
        ?>
    </div>
</div>


            <div class="col-md-4">
    <div class="single-product-widget">
        <h2 class="product-wid-title">Sản Phẩm</h2>
        <a href="shop.php" class="wid-view-more">Xem tất</a>

        <?php
        include 'config.php';

        // Fetch products with is_new = 1
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.active != 2 AND p.is_new = 1 ORDER BY p.sort_order";
        $result = mysqli_query($con, $sql);
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $counter = 0; // Counter variable to limit the number of products

        foreach ($products as $product) {
            if ($counter >= 3) { // Break the loop after three iterations
                break;
            }
            $counter++;
        ?>
            <div class="single-wid-product">
                <a href="single-product.php?id=<?php echo $product['id']; ?>"><img src="img/<?php echo $product['image_url']; ?>" alt="" class="product-thumb"></a>
                <h2><a href="single-product.php?id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></h2>
                <div class="product-wid-price">
                    <ins><?php echo number_format($product['price']); ?>đ</ins> <del><?php echo number_format($product['old_price']); ?>đ</del>
                </div>
            </div>
        <?php
        }
        mysqli_close($con);
        ?>
    </div>
</div>
<div class="col-md-4">
    <div class="single-product-widget">
        <h2 class="product-wid-title">Mới Nhất</h2>
        <a href="shop.php" class="wid-view-more">Xem tất</a>

        <?php
        include 'config.php';

        // Fetch products with is_new = 1
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url FROM products p LEFT JOIN brands b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.active != 2 AND p.is_new = 1 ORDER BY p.sort_order";
        $result = mysqli_query($con, $sql);
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $counter = 0; // Counter variable to limit the number of products

        foreach ($products as $product) {
            if ($counter >= 3) { // Break the loop after three iterations
                break;
            }
            $counter++;
        ?>
            <div class="single-wid-product">
                <a href="single-product.php?id=<?php echo $product['id']; ?>"><img src="img/<?php echo $product['image_url']; ?>" alt="" class="product-thumb"></a>
                <h2><a href="single-product.php?id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></h2>
                <div class="product-wid-price">
                    <ins><?php echo number_format($product['price']); ?>đ</ins> <del><?php echo number_format($product['old_price']); ?>đ</del>
                </div>
            </div>
        <?php
        }
        mysqli_close($con);
        ?>
    </div>
</div>

        </div>
    </div>
</div>
</div> <!-- End product widget area -->
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