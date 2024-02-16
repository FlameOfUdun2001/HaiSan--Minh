<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

// Delete
if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE products SET active = 2 WHERE id = $id";
    if (mysqli_query($con, $sql)) {
        $successMessage = "Product deleted successfully";
    } else {
        $errorMessage = "Error deleting product: " . mysqli_error($con);
    }
}

$search = '';
if (isset($_GET['search_term'])) {
    $search = trim($_GET['search_term']);
}

// Select
$sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_url
        FROM products p
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN product_images pi ON p.id = pi.product_id
        WHERE p.active != 2
            AND (p.name LIKE '%{$search}%' OR p.description LIKE '%{$search}%')
            AND pi.sort_order = 1
        ORDER BY p.sort_order";

$result = mysqli_query($con, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<link rel="stylesheet" href="style.css">
<div>
    <ul class="container"><a href="add_product.php" class="btn btn-primary">Thêm sản phẩm</a></ul>
</div>
<div class="container">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="small-column">ID</th>
                <th class="medium-column">Danh mục</th>
                <th>Xuất sứ</th>
                <th>Tên hải sản</th>
                <th>Ảnh</th>
                <th>Giá</th>
                <th>Giá cũ</th>
                <th>Tags</th>
                <th>Bán chạy</th>
                <th>Mới nhất</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td class="small-column"><?php echo $product['id']; ?></td>
                    <td class="medium-column"><?php echo $product['category_name']; ?></td>
                    <td><?php echo $product['brand_name']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><img src='<?php echo $product['image']; ?>' alt='Product Image' width=100></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['old_price']; ?></td>
                    <td class="small-column"><?php echo $product['tags']; ?></td>

                    <td>
                        <?php if ($product['is_best_sell'] == 1) { ?>
                            <span class="icon-ok"></span>
                        <?php } elseif ($product['is_best_sell'] == 0) { ?>
                            <span class="icon-remove"></span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($product['is_new'] == 1) { ?>
                            <span class="icon-ok"></span>
                        <?php } elseif ($product['is_new'] == 0) { ?>
                            <span class="icon-remove"></span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href=" edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-small btn-primary"> Sửa</a>
                        <a href="product.php?action=delete&id=<?php echo $product['id']; ?>" class="btn btn-small btn-danger"> Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="footer1">
    <?php include 'inc/footer.php'; ?>
</div>