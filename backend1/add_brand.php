<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';


$formData = [
    'name' => '',
    'sort_order' => '',
    'link' => ''
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $link = trim($_POST['link']);
    $sortOrder = trim($_POST['sort_order']);
    $active = isset($_POST['active']) ? 1 : 0;

    // Check errors
    if (empty($name)) {
        $errors['name'] = "Vui lòng nhập tên.";
    } else {
        $formData['name'] = $name;
    }

    if (empty($link)) {
        $errors['link'] = "Vui lòng nhập nổi bật.";
    } else {
        $formData['link'] = $link;
    }

    if (empty($sortOrder) || !is_numeric($sortOrder)) {
        $errors['sort_order'] = "Vui lòng nhập đúng thứ tự có hiệu lực.";
    } else {
        $formData['sort_order'] = $sortOrder;
    }

    // Check for errors image
    $targetDirectory = 'img/';
    $targetFile = $targetDirectory . basename($_FILES['image']['name']);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedFileTypes)) {
        $errors['image'] = "File không đúng định dạng: " . $imageFileType . ". Cho phép filr: " . implode(", ", $allowedFileTypes);
    } else if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        if (empty($errors)) {
            $sql = "INSERT INTO brands (`name`, `image_url`, `link`, `sort_order`, `active`) VALUES ('$name', '$targetFile', '$link', '$sortOrder', '$active')";

            if (mysqli_query($con, $sql)) {
                $successMessage = "Thêm xuất sứ thành công";
            } else {
                $errorMessage = "Lỗi thêm xuất sứ: " . mysqli_error($con);
            }
        }
    } else {
        $errorMessage = "Lỗi thêm ảnh: " . $_FILES['image']['error'];
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
                        <h3>Thêm xuất sứ</h3>
                    </div>

                    <div class="widget-content">
                        <form method="post" enctype="multipart/form-data">
                            <fieldset>
                                <table>
                                    <tr>
                                        <td><label class="control-label" for="name">Nơi xuất sứ<span class="red"> *</span></label></td>
                                        <td>
                                            <div class="controls"><input type="text" class="span6" id="name" name="name" value="<?php echo $formData['name']; ?>"><br>
                                                <?php if (!empty($errors['name'])) : ?>
                                                    <span class="help-inline error"><?php echo $errors['name']; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="control-label" for="image">Ảnh<span class="red"> *</span></label></td>
                                        <td>
                                            <div class="controls"><input type="file" class="form-control-file" id="image" name="image" accept="image/*"><br>
                                                <?php if (!empty($errors['image'])) : ?>
                                                    <span class="help-inline error"><?php echo $errors['image']; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="control-label" for="link">Nổi bật<span class="red"> *</span></label></td>
                                        <td>
                                            <div class="controls"><input type="text" class="span6" id="link" name="link" value="<?php echo $formData['link']; ?>"><br>
                                                <?php if (!empty($errors['link'])) : ?>
                                                    <span class="help-inline error"><?php echo $errors['link']; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="control-label" for="sort_order">Thứ tự sắp xếp<span class="red"> *</span></label></td>
                                        <td>
                                            <div class="controls"><input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo $formData['sort_order']; ?>"><br>
                                                <?php if (!empty($errors['sort_order'])) : ?>
                                                    <span class="help-inline error"><?php echo $errors['sort_order']; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="controls">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="active" value="1" checked> Active
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Thêm xuất sứ</button>
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