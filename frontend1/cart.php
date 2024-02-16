<?php
session_start();
include 'common/header.php';
include 'config.php';

// Define a search term if needed
$search = '';
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['add_to_cart'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Add the product to the cart
        // Fetch product details from the database using the product_id
        $product_query = "SELECT p.*, b.name AS brand_name, c.name AS category_name, pi.image_url
            FROM products p
            LEFT JOIN brands b ON p.brand_id = b.id
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_images pi ON p.id = pi.product_id
            WHERE p.id = $product_id
            AND p.active != 2";

        $product_result = mysqli_query($con, $product_query);

        if ($product_result && mysqli_num_rows($product_result) > 0) {
            $product = mysqli_fetch_assoc($product_result);

            // Ensure 'image_url' is defined in the cart item
            $image_url = isset($product['image_url']) ? $product['image_url'] : '';

            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'image_url' => $image_url,
            ];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    $cart_items = $_SESSION['cart'];

    foreach ($cart_items as $product_id => $item) {
        $new_quantity = $_POST['quantity'][$product_id];

        if ($new_quantity <= 0) {
            // Remove item from cart if quantity is zero or less
            unset($_SESSION['cart'][$product_id]);
        } else {
            // Update quantity of item in cart
            $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        }
    }
}

// Calculate the subtotal
$subtotal = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $price = $item['price'];
        $quantity = $item['quantity'];

        // Calculate the total price for this item
        $total_price = $price * $quantity;
        $subtotal += $total_price;
    }
}

// Store the subtotal in the session
$_SESSION['subtotal'] = $subtotal;
?>
<div class="maincontent-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="product-content-right">
                    <div class="woocommerce">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <table cellspacing="0" class="shop_table cart">
                                <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp;</th>
                                        <th class="product-thumbnail">Hải sản</th>
                                        <th class="product-name">Tên</th>
                                        <th class="product-price">Giá</th>
                                        <th class="product-quantity">Số lượng</th>
                                        <th class="product-subtotal">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($_SESSION['cart'])) {
                                        $subtotal = 0;
                                        foreach ($_SESSION['cart'] as $product_id => $item) {
                                            $name = $item['name'];
                                            $price = $item['price'];
                                            $quantity = $item['quantity'];
                                            $image_url = $item['image_url'];

                                            // Calculate the total price for this item
                                            $total_price = $price * $quantity;
                                            $subtotal += $total_price;
                                    ?>
                                            <tr class="cart_item">
                                                <td class="product-remove">
                                                    <form method="post" action="">
                                                        <input type="hidden" name="remove_item" value="<?php echo $product_id; ?>">
                                                        <button type="submit" class="remove-button" title="Remove this item">X</button>
                                                    </form>
                                                </td>

                                                <td class="product-thumbnail">
                                                    <a href="#"><img width="145" height="145" alt="<?php echo $name; ?>" class="shop_thumbnail" src="img/<?php echo $image_url; ?>"></a>
                                                </td>

                                                <td class="product-name">
                                                    <?php echo $name; ?>
                                                </td>

                                                <td class="product-price">
                                                    <span class="amount"><?php echo number_format($price); ?> đ</span>
                                                </td>

                                                <td class="product-quantity">
                                                    <div class="quantity buttons_added">
                                                        <input type="button" class="minus" value="-" onclick="updateQuantity(<?php echo $product_id; ?>, -1)">
                                                        <span class="quantity">
                                                            <input type="number" class="input-text qty text" title="Qty" name="quantity[<?php echo $product_id; ?>]" id="quantity-<?php echo $product_id; ?>" value="<?php echo $quantity; ?>" min="0" step="1">
                                                        </span>
                                                        <input type="button" class="plus" value="+" onclick="updateQuantity(<?php echo $product_id; ?>, 1)">
                                                    </div>
                                                </td>

                                                <td class="product-subtotal">
                                                    <span class="amount"><?php echo number_format($total_price); ?> đ</span>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td class='actions' colspan='6'>Giỏ hàng của bạn đang trống.</td></tr>";
                                    }
                                    ?>
                                    <tr>
                                        <td class="actions" colspan="6">
                                            <input type="submit" value="Update Giỏ hàng" name="update_cart" class="button">
                                            <form method="post" action="checkout.php?subtotal=<?php echo number_format($subtotal); ?>">
                                                <input type="hidden" name="subtotal" value="<?php echo number_format($subtotal); ?>">
                                                <input type="submit" value="Thanh Toán" name="proceed" class="checkout-button button alt wc-forward">
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>

                        <div class="cart_totals">
                            <h2>Tổng Tiền</h2>
                            <table cellspacing="0">
                                <tbody>
                                    <tr class="cart-subtotal">
                                        <th>Tổng phụ</th>
                                        <td><span class="amount"><?php echo number_format($subtotal); ?>đ</span></td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>Tổng cộng</th>
                                        <td><strong><span class="amount"><?php echo number_format($subtotal); ?>đ</span></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    function updateQuantity(productId, change) {
        let qtyElement = document.getElementById('quantity-' + productId);
        let currentQuantity = parseInt(qtyElement.value);
        let newQuantity = currentQuantity + change;

        if (newQuantity >= 0) {
            qtyElement.value = newQuantity;

            // Update the cart's quantity in the session
            let formData = new FormData();
            formData.append('product_id', productId);
            formData.append('new_quantity', newQuantity);

            fetch('update_cart.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cart quantity updated successfully
                    } else {
                        // Handle the error if needed
                        console.error(data.message);
                    }
                })
                .catch(error => {
        console.error('Error updating cart quantity:', error);
      });
    }
  }
</script>
<?php include 'common/brandindex.php'; ?>
<?php include 'common/seller.php'; ?>
<?php include 'common/footer.php'; ?>
