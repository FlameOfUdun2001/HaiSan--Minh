<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

$formData = [
    'name' => '',
    'sortOrder' => ''
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $sortOrder = trim($_POST['sort_order']);
    $active = isset($_POST['active']) ? 1 : 0;

    // Validate form field values
    if (empty($name)) {
        $errors['name'] = "Vui lòng nhập tên.";
    } else {
        $formData['name'] = $name;
    }

    if (empty($sortOrder) || !is_numeric($sortOrder)) {
        $errors['sortOrder'] = "Vui lòng nhập đúng thứ tự có hiệu lực.";
    } else {
        $formData['sort_order'] = $sortOrder;
    }

    // If form validation passes, insert category into database
    if (empty($errors)) {
        $sql = "INSERT INTO categories (`name`, `sort_order`, `active`) VALUES ('$name', '$sortOrder', '$active')";

        if (mysqli_query($con, $sql)) {
            $successMessage = "Thêm danh mục thành công ";
        } else {
            $errorMessage = "Lỗi thêm danh mục: " . mysqli_error($con);
        }
    }
}
?>

<div class="main-inner">
    <div class="container">
        <?php if (isset($successMessage)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errorMessage)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="span12">
                <div class="widget-header">
                    <i class="icon-edit"></i>
                    <h3>Thêm danh mục</h3>
                </div>
                <div class="widget-content">
                    <form method="post">
                        <fieldset>
                            <table>
                                <tr>
                                    <td><label class="control-label" for="name">Tên danh mục<span class="red"> *</span></label></td>
                                    <td>
                                        <div class="controls"><input type="text" class="span6" id="name" name="name" value="<?php echo $formData['name']; ?>"><br>
                                            <?php if (isset($errors['name'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['name']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="control-label" for="sort_order">Thứ tự sắp xếp<span class="red"> *</label></td>
                                    <td>
                                        <div class="controls"><input type="number" class="span6" id="sort_order" name="sort_order" value="<?php echo $formData['sortOrder']; ?>"><br>
                                            <?php if (isset($errors['sortOrder'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['sortOrder']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Active<span class="red"> *</span></label></td>
                                    <td>
                                        <div class="controls">
                                            <label class="checkbox">
                                                <input type="checkbox" name="active" value="1">
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Thêm danh mục</button>
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

<div class="footer1">
    <?php include 'inc/footer.php'; ?>
</div>