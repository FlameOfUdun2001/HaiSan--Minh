<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

// Delete brand if the delete button was clicked

if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE brands SET active = 2 WHERE id = $id";
    if (mysqli_query($con, $sql)) {
        $successMessage = "Brand deleted successfully";
    } else {
        $errorMessage = "Error deleting brand: " . mysqli_error($con);
    }
}

$sql = "SELECT * FROM brands WhERE active != 2 ORDER BY sort_order ";
$result = mysqli_query($con, $sql);
$brands = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div>
    <ul class="container"><a href="add_brand.php" class="btn btn-primary">Thêm nơi xuất sứ</i></a></ul>
</div>
<div class="container">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nơi xuất sứ</th>
                <th>Ảnh</th>
                <th>Link</th>
                <th>Active</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($brands as $brand) { ?>
                <tr>
                    <td><?php echo $brand['id'] ?></td>
                    <td><?php echo $brand['name'] ?></td>
                    <td><img src="<?php echo $brand['image_url']; ?>" width=100 alt=""></td>
                    <td><?php echo $brand['link'] ?></td>
                    <td>
                        <?php if ($brand['active'] == 1) { ?>
                            <span class="icon-ok"></span>
                        <?php } elseif ($brand['active'] == 0) { ?>
                            <span class="icon-remove"></span>
                        <?php } ?>
                    </td>
                    <td>
                        <a class="btn btn-info" href="edit_brand.php?id=<?php echo $brand['id'] ?>">Sửa</a>
                        <a class="btn btn-danger" href="brands.php?action=delete&id=<?php echo $brand['id'] ?>" onclick="return confirm('Are you sure you want to delete this brand?');">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Display success or error message if set -->
    <?php if (isset($successMessage)) { ?>
        <div class="alert alert-success"><?php echo $successMessage ?></div>
    <?php } ?>

    <?php if (isset($errorMessage)) { ?>
        <div class="alert alert-danger"><?php echo $errorMessage ?></div>
    <?php } ?>

</div><!-- /container -->

<div class="footer1">
    <?php include 'inc/footer.php'; ?>
</div>