<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';
?>



<div class="main-inner">
  <div class="container">
    <div class="row">
      <div class="span12">
        <div class="widget-header"> <i class="icon-home"></i>
          <h3>Trang chủ</h3>
        </div>
        <!-- /widget-header -->
        <div class="widget-content">
          <div class="shortcuts">
            <a href="category.php" class="shortcut"><i class="shortcut-icon icon-tags"></i><span class="shortcut-label">Danh mục</span></a>
            <a href="brands.php" class="shortcut"><i class="shortcut-icon icon-camera"></i><span class="shortcut-label">Xuất sứ</span></a>
            <a href="product.php" class="shortcut"><i class="shortcut-icon icon-gift"></i><span class="shortcut-label">Hải Sản</span></a>
            <a href="order.php" class="shortcut"><i class="shortcut-icon icon-truck"></i><span class="shortcut-label">Order</span></a>
            <a href="users.php" class="shortcut"><i class="shortcut-icon icon-user"></i><span class="shortcut-label">Người dùng</span></a>
            <a href="banner.php" class="shortcut"><i class="shortcut-icon icon-bookmark"></i><span class="shortcut-label">Banner</span></a>
          </div>
          <!-- /shortcuts -->
        </div>
        <!-- /widget-content -->
      </div>
    </div>
  </div>

  <div class="footer1">
    <?php include 'inc/footer.php'; ?>
  </div>