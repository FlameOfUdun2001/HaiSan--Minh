<?php
$search = '';
if (isset($_GET['search_term'])) {
    $search = trim($_GET['search_term']);
}
?>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.php">Trang chủ nhân viên </a>
            <div class="nav-collapse">
                <ul class="nav pull-right">
                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i> Tài khoản  <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:;">Cài đặt</a></li>
                            <li><a href="javascript:;">Hỗ trợ</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> Thêm <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:;">Profile</a></li>
                            <li><a href="javascript:;">Đăng xuất</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="navbar-search pull-right" action="" method="GET">
                    <input type="text" class="search-query" placeholder="Tìm kiếm" name="search_term" value="<?php echo $search ?>">
                </form>
            </div>
            <!--/.nav-collapse -->
        </div>
        <!-- /container -->
    </div>
    <!-- /navbar-inner -->
</div>
<!-- /navbar -->
<div class="subnavbar">
    <div class="subnavbar-inner">
        <div class="container">
            <ul class="mainnav">
                <li class="active"><a href="index.php"><i class="icon-home"></i><span>Trang chủ</span> </a> </li>
                <li><a href="category.php"><i class="icon-tags"></i><span>Danh mục</span> </a> </li>
                <li><a href="brands.php"><i class="icon-camera"></i><span>Xuất xứ</span> </a></li>
                <li><a href="product.php"><i class="icon-gift"></i><span>Hải sản</span> </a> </li>
                <li><a href="order.php"><i class="icon-truck"></i><span>Order</span> </a> </li>
                <li><a href="users.php"><i class="icon-user"></i><span>Người dùng</span> </a> </li>
                <li><a href="banner.php"><i class=" icon-bookmark"></i><span>Banners</span> </a> </li>
                <!-- <li><a href="register.php"><i class=" icon-key"></i><span>Register</span> </a> </li> -->
                <li><a href="chart.php"><i class=" icon-key"></i><span>Báo cáo doanh thu</span> </a> </li>
                <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-long-arrow-down"></i><span>Drops</span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="index.php">Trang chủ</a></li>
                        <li><a href="category.php">Danh mục</a></li>
                        <li><a href="brands.php">Xuất xứ</a></li>
                        <li><a href="product.php">Hải sản</a></li>
                        <li><a href="image_product.php">Ảnh</a></li>
                        <li><a href="order.php">Order</a></li>
                        <li><a href="users.php">Users</a></li>
                        <li><a href="banner.php">Banners</a></li>
                        <!-- <li><a href="register.php">Register</a></li> -->
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /container -->
    </div>
    <!-- /subnavbar-inner -->
</div>
<!-- /subnavbar -->