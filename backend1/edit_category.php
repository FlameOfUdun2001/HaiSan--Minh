<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

?>
<?php
// Get the category to be edited based on the ID passed as a query parameter
if (isset($_GET['id'])) {
    $id = trim($_GET['id']);
    // Retrieve the category details from the database and set the values in the $formData array
    $sql = "SELECT * FROM categories WHERE id='$id'";
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $formData['name'] = $row['name'];
        $formData['sort_order'] = $row['sort_order'];
        $formData['active'] = $row['active'];
    } else {
        $errorMessage = "Category not found.";
    }
}
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = trim($_POST['id']);
    $name = trim($_POST['name']);
    $sortOrder = trim($_POST['sort_order']);
    $active = isset($_POST['active']) ? 1 : 0;
    if (empty($name)) {
        $errors['name'] = "Vui lòng nhập danh mục.";
    }

    if (empty($sortOrder) || !is_numeric($sortOrder)) {
        $errors['sort_order'] = "Vui lòng nhập đúng kiểu dữ liệu .";
    }

    if (empty($errors)) {
        // Update the category in the database
        $sql = "UPDATE categories SET name='$name', sort_order='$sortOrder', active='$active' WHERE id='$id'";

        if (mysqli_query($con, $sql)) {
            $successMessage = "Sửa danh mục thành công";
        } else {
            $errorMessage = "Lỗi khi sửa danh mục: " . mysqli_error($con);
        }
    }
}

?>

<div class="main-inner">
    <div class="container">
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
        <div class="row">
            <div class="span12">
                <div class="widget">
                    <div class="widget-header">
                        <i class="icon-edit"></i>
                        <h3>Sửa Danh mục</h3>
                    </div>

                    <div class="widget-content">
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <fieldset>
                                <table>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="name">Tên danh mục<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <input type="text" class="span6" id="name" name="name" value="<?php echo $formData['name']; ?>"><br>
                                            <?php if (isset($errors['name'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['name']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="sort_order">Thứ tự sắp xếp<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <input type="text" class="span6" id="sort_order" name="sort_order" value="<?php echo $formData['sort_order']; ?>"><br>
                                            <?php if (isset($errors['sort_order'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['sort_order']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="active">Active:</label>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="active" name="active" <?php if ($formData['active'] ?? false) echo "checked"; ?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Update danh mục</button>
                                                <a class="btn" href="category.php">Hủy</a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer1">
    <?php include 'inc/footer.php'; ?>
</div>