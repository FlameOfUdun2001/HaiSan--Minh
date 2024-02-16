<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

// Delete category if the delete button was clicked
if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE categories SET active = 2 WHERE id = $id";
    if (mysqli_query($con, $sql)) {
        $successMessage = "Category deleted successfully";
    } else {
        $errorMessage = "Error deleting category: " . mysqli_error($con);
    }
}
$search = '';
if (isset($_GET['search_term'])) {
    $search = trim($_GET['search_term']);
}

// Get all categories from the database
$sql = "SELECT * FROM categories WHERE active != 2 ORDER BY sort_order";
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div>
    <ul class="container"><a href="add_category.php" class="btn btn-primary">Thêm danh mục</a></ul>
</div>
<div class="container">
    <table class="table custom-table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Active</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category) { ?>
                <tr>
                    <td id="id"><?php echo $category['id']; ?></td>
                    <td id="name"><?php echo $category['name']; ?></td>
                    <td>
                        <?php if ($category['active'] == 1) { ?>
                            <span class="icon-ok"></span>
                        <?php } elseif ($category['active'] == 0) { ?>
                            <span class="icon-remove"></span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="btn btn-primary btn-sm">Sửa</a>
                        <a href="category.php?action=delete&id=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?')">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="footer1">
    <?php include 'inc/footer.php'; ?>
</div>