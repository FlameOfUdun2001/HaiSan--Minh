<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hải sản USTORA</title>

    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,700,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,100' rel='stylesheet' type='text/css'>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
    /* Adjust the width of the dropdown menu items */
    .mainmenu-area .navbar-nav .dropdown-menu .custom-dropdown-item {
        width: 138.4px; /* Set the desired width */
    }
</style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>

<body>

    <div class="header-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="user-menu">
                        <ul>
                            <li><a href="#"><i class="fa fa-user"></i> Tài khoản của tôi</a></li>

                            <li><a href="cart.php"><i class="fa fa-user"></i> Giỏ hàng</a></li>
                            <li><a href="checkout.php"><i class="fa fa-user"></i> Thanh toán</a></li>
                            <li><a href="checkout.php"><i class="fa fa-user"></i> Đăng nhập</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="header-right">
                        <ul class="list-unstyled list-inline">
                            <li class="dropdown dropdown-small">
                                <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" href="#"><span class="key">Mệnh giá :</span><span class="value">VND </span><b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">VND</a></li>
                                    <li><a href="#">USD</a></li>
                                    <li><a href="#">EURO</a></li>
                                </ul>
                            </li>

                            <li class="dropdown dropdown-small">
                                <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" href="#"><span class="key">Ngôn ngữ :</span><span class="value">VNM </span><b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">VNM</a></li>
                                    <li><a href="#">ENGLISH</a></li>
                                    <li><a href="#">German</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End header area -->

    <div class="site-branding-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="logo">
                        <h1><a href="./"><img src="img/logo.png"></a></h1>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="shopping-item">
                        <?php
                        $totalQuantity = 0;
                        $totalPrice = 0;

                        if (!empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $product_id => $item) {
                                $totalQuantity += $item['quantity'];
                                $totalPrice += $item['price'] * $item['quantity'];
                            }
                        }
                        ?>
                        <a href="checkout.php">Giỏ hàng - <span class="cart-amunt"><?php echo number_format($totalPrice, 2); ?>đ</span> <i class="fa fa-shopping-cart"></i> <span class="product-count"><?php echo $totalQuantity; ?></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End site branding area -->

    <div class="mainmenu-area">
        <div class="container">
            <div class="row">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php">Trang Chủ</a></li>
                        <li class="dropdown">
                            <a href="ca.php" class="dropdown-toggle" data-toggle="dropdown">Danh mục<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="ca.php">Cá</a></li>
                                <li><a href="tom.php">Tôm</a></li>
                                <li><a href="cua.php">Cua</a></li>
                                <li><a href="muc.php">Mực</a></li>
                            </ul>
                        </li>
                        <li><a href="cart.php">Giỏ hàng</a></li>
                        <li><a href="checkout.php">Thanh Toán</a></li>
                        <li><a href="shop.php">Sản Phẩm</a></li>
                        <li><a href="calory.php">Calories</a></li>
                        <li><a href="register.php">Đăng Ký</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- End mainmenu area -->
    <script>
    $(document).ready(function() {
        // Get the current page URL
        var currentUrl = window.location.href;

        // Iterate through each menu item
        $('.mainmenu-area .navbar-nav li a').each(function() {
            // Check if the current URL contains the href of the menu item
            if (currentUrl.indexOf($(this).attr('href')) > -1) {
                // Add the "active" class to the parent li
                $(this).closest('li').addClass('active');
            }
        });

        // Check if the current page is one of the specific pages
        if (currentUrl.indexOf('ca.php') > -1 || currentUrl.indexOf('tom.php') > -1 || currentUrl.indexOf('cua.php') > -1 || currentUrl.indexOf('muc.php') > -1) {
            // Add the "active" class to the "Danh mục" menu item
            $('.mainmenu-area .navbar-nav li.dropdown a').closest('li').addClass('active');
        }
    });
</script>
</body>
</html>