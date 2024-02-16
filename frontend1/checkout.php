<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form submission
    if (isset($_POST['woocommerce_checkout_place_order'])) {
        $payment_method = $_POST['payment_method'];

        if ($payment_method == 'bacs') {
            // Handle "Payment on delivery" option here

            $customer_name = ''; // Initialize customer_name

            // Check if there are products in the cart
            if (!empty($_SESSION['cart'])) {
                $productNames = array();
                $productQuantities = array(); // Create an array to store quantities

                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $product_name = $item['name'];
                    $productNames[] = $product_name;
                    $productQuantities[] = $item['quantity']; // Get the quantity from the cart item
                }
                $customer_name = implode(', ', $productNames);
                $productQuantitiesString = implode(', ', $productQuantities); // Format quantity as "x1, x2, x3", etc.
                $total_products = array_sum($productQuantities); // Calculate the total quantity of products
            } else {
                $customer_name = 'Vui lòng đăng nhập';
                $productQuantitiesString = ''; // Set product quantities string to empty
                $total_products = 0;
            }

            $customer_phone = 'Thanh toán khi nhận hàng'; // Set customer_phone to "Payment on delivery"
            $total_money = isset($_SESSION['subtotal']) ? $_SESSION['subtotal'] : 0;

            // Store the customer information in the "orders" table, including product quantities in customer_email
            $conn = mysqli_connect("localhost", "root", "", "sell_fishs");
            $sql = "INSERT INTO orders (customer_name, customer_phone, customer_email, total_money, total_products, created_date, status)
                    VALUES ('$customer_name', '$customer_phone', '$productQuantitiesString', '$total_money', '$total_products', NOW(), '{$_SESSION['user_id']}')";
            mysqli_query($conn, $sql);
            mysqli_close($conn);

            if (isset($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
            $total = $total_money;
            if (!empty($_SESSION['subtotal'])) {
                $total = $_SESSION['subtotal'];
            }
            echo "<script>alert('Thanh toán thành công');</script>";
        } elseif ($payment_method == 'cheque') {
            // Handle the "Online Payment" option, or redirect to another page
            // Store the customer information in the "orders" table, with a different phone message
            $customer_name = ''; // Initialize customer_name

            // Check if there are products in the cart
            if (!empty($_SESSION['cart'])) {
                $productNames = array();
                $productQuantities = array(); // Create an array to store quantities
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $product_name = $item['name'];
                    $productNames[] = $product_name;
                    $productQuantities[] = $item['quantity']; // Get the quantity from the cart item
                }
                $customer_name = implode(', ', $productNames);
                $productQuantitiesString = implode(', ', $productQuantities); // Format quantity as "x1, x2, x3", etc.
                $total_products = array_sum($productQuantities); // Calculate the total quantity of products
            } else {
                $customer_name = 'Vui lòng đăng nhập'; // If the cart is empty, set it to "YOUR ORDER"
                $productQuantitiesString = ''; // Set product quantities string to empty
                $total_products = 0;
            }

            $customer_phone = 'Thanh toán QR MoMo'; // Set customer_phone to "Online Payment"
            $total_money = isset($_SESSION['subtotal']) ? $_SESSION['subtotal'] : 0;

            // Store the customer information in the "orders" table
            $conn = mysqli_connect("localhost", "root", "", "sell_fishs");
            $sql = "INSERT INTO orders (customer_name, customer_phone, customer_email, total_money, total_products, created_date, status)
                    VALUES ('$customer_name', '$customer_phone', '$productQuantitiesString', '$total_money', '$total_products', NOW(), '{$_SESSION['user_id']}')";
            mysqli_query($conn, $sql);
            mysqli_close($conn);

            if (isset($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
            $total = $total_money;
            if (!empty($_SESSION['subtotal'])) {
                $total = $_SESSION['subtotal'];
            }
            echo "<script>alert('Thanh toán thành công');</script>";

            header("Location: offlinepayment.php?price=$total&productName=$customer_name");
            exit;
        }elseif ($payment_method == 'cheque2') {
            // Handle the "Online Payment" option, or redirect to another page
            // Store the customer information in the "orders" table, with a different phone message
            $customer_name = ''; // Initialize customer_name

            // Check if there are products in the cart
            if (!empty($_SESSION['cart'])) {
                $productNames = array();
                $productQuantities = array(); // Create an array to store quantities
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $product_name = $item['name'];
                    $productNames[] = $product_name;
                    $productQuantities[] = $item['quantity']; // Get the quantity from the cart item
                }
                $customer_name = implode(', ', $productNames);
                $productQuantitiesString = implode(', ', $productQuantities); // Format quantity as "x1, x2, x3", etc.
                $total_products = array_sum($productQuantities); // Calculate the total quantity of products
            } else {
                $customer_name = 'Vui lòng đăng nhập'; // If the cart is empty, set it to "YOUR ORDER"
                $productQuantitiesString = ''; // Set product quantities string to empty
                $total_products = 0;
            }

            $customer_phone = 'Thanh toán ATM MoMo'; // Set customer_phone to "Online Payment"
            $total_money = isset($_SESSION['subtotal']) ? $_SESSION['subtotal'] . ' VND' : '0 VND';

            // Store the customer information in the "orders" table
            $conn = mysqli_connect("localhost", "root", "", "sell_fishs");
            $sql = "INSERT INTO orders (customer_name, customer_phone, customer_email, total_money, total_products, created_date, status)
                    VALUES ('$customer_name', '$customer_phone', '$productQuantitiesString', '$total_money', '$total_products', NOW(), '{$_SESSION['user_id']}')";
            mysqli_query($conn, $sql);
            mysqli_close($conn);

            if (isset($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
            $total = $total_money;
            if (!empty($_SESSION['subtotal'])) {
                $total = $_SESSION['subtotal'];
            }
            header("Location: atm_momo.php?amount=$total&productName=$customer_name");
            exit;
        }elseif ($payment_method == 'cheque3') {
            // Handle the "Online Payment" option, or redirect to another page
            // Store the customer information in the "orders" table, with a different phone message
            $customer_name = ''; // Initialize customer_name

            // Check if there are products in the cart
            if (!empty($_SESSION['cart'])) {
                $productNames = array();
                $productQuantities = array(); // Create an array to store quantities
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $product_name = $item['name'];
                    $productNames[] = $product_name;
                    $productQuantities[] = $item['quantity']; // Get the quantity from the cart item
                }
                $customer_name = implode(', ', $productNames);
                $productQuantitiesString = implode(', ', $productQuantities); // Format quantity as "x1, x2, x3", etc.
                $total_products = array_sum($productQuantities); // Calculate the total quantity of products
            } else {
                $customer_name = 'YOUR ORDER'; // If the cart is empty, set it to "YOUR ORDER"
                $productQuantitiesString = ''; // Set product quantities string to empty
                $total_products = 0;
            }

            $customer_phone = 'ATM Payment'; // Set customer_phone to "Online Payment"
            $total_money = isset($_SESSION['subtotal']) ? $_SESSION['subtotal'] : 0;

            // Store the customer information in the "orders" table
            $conn = mysqli_connect("localhost", "root", "", "sell_fishs");
            $sql = "INSERT INTO orders (customer_name, customer_phone, customer_email, total_money, total_products, created_date, status)
                    VALUES ('$customer_name', '$customer_phone', '$productQuantitiesString', '$total_money', '$total_products', NOW(), '{$_SESSION['user_id']}')";
            mysqli_query($conn, $sql);
            mysqli_close($conn);

            if (isset($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
            $total = $total_money;
            if (!empty($_SESSION['subtotal'])) {
                $total = $_SESSION['subtotal'];
            }
            echo "<script>alert('Order Success');</script>";

            header("Location: vnpay_create_payment.php");
            exit;
        }
    }


    // Sanitize input data
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $conn = mysqli_connect("localhost", "root", "", "sell_fishs");
    // Verify user credentials
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            // Redirect to a success page or perform other actions as needed
        } else {
            $errorMessage = "Invalid username or password";
        }
    } else {
        $errorMessage = "Invalid username or password";
    }
}

include 'common/header.php';

$total = isset($_SESSION['subtotal']) ? $_SESSION['subtotal'] : 0;


?>

<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Giỏ Hàng</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="product-content-right">
                    <div class="woocommerce">
                        <div class="woocommerce-info">Đăng nhập? <a class="showlogin" data-toggle="collapse" href="#login-form-wrap" aria-expanded="false" aria-controls="login-form-wrap">Nhấn vào để đăng nhập</a></div>

                        <form id="login-form-wrap" class="login collapse" method="post">

                            <p class="form-row form-row-first">
                                <label for="username">Tên đăng nhập <span class="required">*</span></label>
                                <input type="text" id="username" name="username" class="input-text" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </p>
                            <p class="form-row form-row-last">
                                <label for="password">Password <span class="required">*</span></label>
                                <input type="password" id="password" name="password" class="input-text" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
                            </p>
                            <div class="clear"></div>

                            <p class="form-row">
                                <input type="submit" value="Login" name="login" class="button">
                                <label class="inline" for="rememberme"><input type="checkbox" value="forever" id="rememberme" name="rememberme"> Remember me </label>
                            </p>
                            <p class="lost_password">
                                <a href="#">Lost your password?</a>
                            </p>

                            <div class="clear"></div>
                        </form>

                        <form enctype="multipart/form-data" action="#" class="checkout" method="post" name="checkout">
                            <h3 id="order_review_heading">Order của bạn</h3>
                            <div id="order_review" style="position: relative;">
                                <table class="shop_table">

                                <tbody>
                                    <?php
                                    if (!empty($_SESSION['cart'])) {
                                        $productNames = array();
                                        $productQuantities = array(); // Create an array to store quantities

                                        foreach ($_SESSION['cart'] as $product_id => $item) {
                                            $name = $item['name'];
                                            $quantity = $item['quantity']; // Get the quantity from the cart item

                                            $productNames[] = $name;
                                            $productQuantities[] = "x" . $quantity; // Format quantity as "x1", "x2", etc.
                                        }

                                        $productNamesString = implode(', ', $productNames);
                                        $productQuantitiesString = implode(', ', $productQuantities);

                                        // Display product names and quantities
                                        ?>
                                        <tr class="cart_item">
                                            <th class="product-name">Hải Sản</th>
                                            <td class="product-names"><?php echo $productNamesString; ?></td>
                                        </tr>
                                        <tr class="cart_item">
                                            <th class="product-name">Số lượng mua mỗi sản phẩm</th>
                                            <td class="product-names"><?php echo $productQuantitiesString; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                                </tbody>
                                    <tfoot>
                                        <tr class="shipping">
                                            <th>Tên người dùng</th>
                                            <td>
                                                <?php
                                                if (isset($_SESSION['user_id'])) {
                                                    // Get the username from the database based on user_id
                                                    $user_id = $_SESSION['user_id'];
                                                    $conn = mysqli_connect("localhost", "root", "", "sell_fishs");
                                                    $sql = "SELECT username FROM users WHERE id = '$user_id'";
                                                    $result = mysqli_query($conn, $sql);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        $row = mysqli_fetch_assoc($result);
                                                        echo $row['username'];
                                                    } else {
                                                        echo "Username not found";
                                                    }

                                                    mysqli_close($conn);
                                                } else {
                                                    echo "Vui lòng đăng nhập"; // Default name if the user is not logged in
                                                }
                                                ?>
                                                <input type="hidden" class="shipping_method" value="free_shipping" id="shipping_method_0" data-index="0" name="shipping_method[0]">
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <th>Tổng tiền</th>
                                            <td><strong><span class="amount"><?php echo number_format($total); ?> đ</span></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="payment">
                                    <ul class="payment_methods methods">
                                        <li class="payment_method_bacs">
                                            <input type="radio" data-order_button_text="" checked="checked" value="bacs" name="payment_method" class="input-radio" id="payment_method_bacs">
                                            <label for="payment_method_bacs">Thanh toán khi nhận hàng </label>
                                            <div class="payment_box payment_method_bacs">
                                                <p>Payment when arrived</p>
                                            </div>

                                            <input type="radio" data-order_button_text="" value="cheque" name="payment_method" class="input-radio" id="payment_method_cheque" <?php if (!empty($_SESSION['cart']['payment_method']) && $_SESSION['cart']['payment_method'] == 'cheque') { echo 'checked="checked"'; } ?>>
                                            <label for="payment_method_cheque">Thanh toán bằng QR MoMo </label>
                                            <div style="display:none;" class="payment_box payment_method_cheque">
                                                <p>Please send your cheque to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</p>
                                            </div>
                                            <br>
                                            <input type="radio" data-order_button_text="" value="cheque2" name="payment_method" class="input-radio" id="payment_method_cheque2" <?php if (!empty($_SESSION['cart']['payment_method']) && $_SESSION['cart']['payment_method'] == 'cheque2') { echo 'checked="checked"'; } ?>>
                                            <label for="payment_method_cheque2">Thanh toán bằng ATM MoMo </label>
                                            <div style="display:none;" class="payment_box payment_method_cheque2">
                                                <p>Additional information for ATM Payment.</p>
                                                <!-- You can include any additional information or instructions here -->
                                            </div>

                                        </li>
                                    </ul>
                                    <div class="form-row place-order">
                                        <input type="submit" data-value="Place order" value="Place order" id="place_order" name="woocommerce_checkout_place_order" class="button alt">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php include 'common/footer.php'; ?>