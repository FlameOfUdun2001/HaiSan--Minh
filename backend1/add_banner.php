<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

$formData = [
    'title' => '',
    'content' => '',
    'sort_order' => ''
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $sortOrder = trim($_POST['sort_order']);
    $active = isset($_POST['active']) ? 1 : 0;

    // Check errors
    if (empty($title)) {
        $errors['title'] = "Vui lòng nhập title.";
    } else {
        $formData['title'] = $title;
    }

    if (empty($content)) {
        $errors['content'] = "Vui lòng thêm content.";
    } else {
        $formData['content'] = $content;
    }

    if (empty($sortOrder) || !is_numeric($sortOrder)) {
        $errors['sort_order'] = "Vui lòng nhập đúng kiểu dữ liệu.";
    } else {
        $formData['sort_order'] = $sortOrder;
    }

    // Check for errors image
    $targetDirectory = 'img/';
    $targetFile = $targetDirectory . basename($_FILES['image']['name']);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedFileTypes)) {
        $errors['image'] = "Không đúng kiểu dữ liệu: " . $imageFileType . ".Cho phép: " . implode(", ", $allowedFileTypes);
    } else if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        if (empty($errors)) {
            $sql = "INSERT INTO banners (`title`, `content`, `image_url`, `sort_order`, `active`)
            VALUES ('$title', '$content', '$targetFile', '$sortOrder', '$active')";

            if (mysqli_query($con, $sql)) {
                $successMessage = "Thêm Banner thành công";
            } else {
                $errorMessage = "Lỗi khi thêm banner: " . mysqli_error($con);
            }
        }
    } else {
        $errorMessage = "Lỗi khi upload ảnh: " . $_FILES['image']['error'];
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
                        <h3>Thêm banner</h3>
                    </div>
                    <div class="widget-content">
                        <form method="post" enctype="multipart/form-data">
                            <fieldset>
                                <table>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="title">Title<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <div class="controls">
                                                <input type="text" class="span6" id="title" name="title" value="<?php echo $formData['title']; ?>"><br>
                                                <?php if (!empty($errors['title'])) : ?>
                                                    <span class="help-inline error"><?php echo $errors['title']; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="image">Ảnh<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <div class="controls">
                                                <input type="file" class="form-control-file" id="image" name="image" accept="image/*"><br>
                                                <?php if (!empty($errors['image'])) : ?>
                                                    <span class="help-inline error"><?php echo $errors['image']; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="content">Content<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <div class="controls">
                                                <textarea class="span6" id="content" name="content"><?php echo $formData['content']; ?></textarea><br>
                                                <?php if (!empty($errors['content'])) : ?>
                                                    <span class="help-inline error"><?php echo $errors['content']; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="sort_order">Thứ tự sắp xếp<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <div class="controls">
                                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo $formData['sort_order']; ?>"><br>
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
                                                <button type="submit" class="btn btn-primary">Thêm Banner</button>
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