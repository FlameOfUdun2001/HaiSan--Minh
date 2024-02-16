<?php
// Connect to the database
$con = mysqli_connect("localhost", "root", "", "sell_fishs");

// Check if the connection was successful
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
?>
<div class="navbar navbar-fixed-top">

    <div class="navbar-inner">

        <div class="container">

            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <a class="brand" href="register.php">
                ĐĂNG KÝ TÀI KHOẢN
            </a>

            <div class="nav-collapse">
                <ul class="nav pull-right">
                    <li class="">
                        <a href="cart.php" class="">
                            Đã có tài khoản ? Đăng nhập ngay
                        </a>

                    </li>
                    <li class="">
                        <a href="index.php" class="">
                            <i class="icon-chevron-left"></i>
                            Trở về trang chủ
                        </a>

                    </li>
                </ul>

            </div>
            <!--/.nav-collapse -->

        </div> <!-- /container -->

    </div> <!-- /navbar-inner -->

</div> <!-- /navbar -->

<!DOCTYPE html>
<html lang="en">

 <head>
    <meta charset="utf-8">
    <title>ĐĂNG KÝ </title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

<link href="css1/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css1/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />

<link href="css1/font-awesome.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">

<link href="css1/style.css" rel="stylesheet" type="text/css">
<link href="css1/pages/signin.css" rel="stylesheet" type="text/css">

</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Check if the  exist before accese information
    $retypePassword = isset($_POST['retype_password']) ? mysqli_real_escape_string($con, $_POST['retype_password']) : '';
    $token = isset($_POST['token']) ? mysqli_real_escape_string($con, $_POST['token']) : '';
    $active = isset($_POST['active']) ? mysqli_real_escape_string($con, $_POST['active']) : '';

    // Check if the passwords match
    if ($password != $retypePassword) {
        $errorMessage = "Passwords do not match";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists in the database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            $errorMessage = "Email already exists in the database";
        } else {
            // Insert user data into the database
            $sql = "INSERT INTO users (`username`, `email`, `password`, `token`, `active`)
                    VALUES ('$username', '$email', '$hashedPassword', '$token', '$active')";

            if (mysqli_query($con, $sql)) {
                $successMessage = "User registered successfully";
            } else {
                $errorMessage = "Error registering user: " . mysqli_error($con);
            }
        }
    }
}
?>

<div class="account-container register">
    <div class="content clearfix">
        <form method="POST" action="">

            <?php if (isset($errorMessage)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($successMessage)) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <h1>Đăng ký </h1>
            <div class="login-fields">
                <p>Đăng ký để mua ngay:</p>
                <div class="field">
                    <label>Tên:</label>
                    <input type="text" class="login" name="username" required placeholder=" Name">
                </div>
                <div class="field">
                    <label>Email Address:</label>
                    <input type="email" class="login" name="email" required placeholder="Email Address">
                </div>
                <div class="field">
                    <label for="password">Password:</label>
                    <input type="password" class="login" name="password" required placeholder="Password">
                </div>
                <div class="field">
                    <label for="confirm_password">Nhập lại Password:</label>
                    <input type="password" class="login" name="retype_password" required placeholder="Confirm Password">
                </div>
                <div class="field">
                    <label>Code ghi nhớ:</label>
                    <input type="text" class="login" name="token" placeholder="Token">
                </div>
            </div> <!-- /login-fields -->
            <div class="login-actions">
                <span class="login-checkbox">
                    <input id="Field" name="active" type="checkbox" class="field login-checkbox" placeholder=">Active" value="1" tabindex="4" />
                    <label class="choice" for="Field">Hải Sản NGOCMINH</label>
                </span>
                <button type="submit" class="button btn btn-primary btn-large">Đăng ký</button>
            </div> <!-- .actions -->
        </form>
    </div> <!-- /content -->
</div> <!-- /account-container -->

<!-- Text Under Box -->
<div class="login-extra">
    Đã có tài khoản? <a href="checkout.php">Đăng nhập ngay</a>
</div> <!-- /login-extra -->

</body>

</html>

<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/excanvas.min.js"></script>
<script src="js/chart.min.js" type="text/javascript"></script>
<script src="js/bootstrap.js"></script>
<script language="javascript" type="text/javascript" src="js/full-calendar/fullcalendar.min.js"></script>

<script src="js/base.js"></script>
<script>