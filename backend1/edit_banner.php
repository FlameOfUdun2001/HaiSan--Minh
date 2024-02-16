<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

$errors = [];
$formData = [];

// Retrieve the banner details from the database and pre-populate the form fields
if (isset($_GET['id'])) {
    $id = trim($_GET['id']);
    $sql = "SELECT * FROM banners WHERE id=$id";
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_array($result)) {
        $formData['title'] = $row['title'];
        $formData['content'] = $row['content'];
        $formData['imageUrl'] = $row['image_url'];
        $formData['sort_order'] = $row['sort_order'];
        $formData['active'] = $row['active'];
    } else {
        $errorMessage = "Banner not found";
    }
}

// Handle form submissions for editing an existing banner
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = trim($_POST['id']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $imageName = $_FILES['image_url']['name'];
    $imageTmpName = $_FILES['image_url']['tmp_name'];
    $sortOrder = trim($_POST['sort_order']);
    $active = isset($_POST['active']) ? 1 : 0;

    // Check for errors in title and sort order
    if (empty($title)) {
        $errors['title'] = "Vui lòng nhập title.";
    } else {
        $formData['title'] = $title;
    }

    if (empty($sortOrder) || !is_numeric($sortOrder)) {
        $errors['sort_order'] = "Vui lòng nhập thứ tự sắp xếp.";
    } else {
        $formData['sort_order'] = $sortOrder;
    }

    // Check whether an image was uploaded or not
    if (!empty($imageName)) {
        $targetDirectory = 'img/';
        $targetFile = $targetDirectory . basename($imageName);

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];

        // Check for errors in image
        if (!in_array($imageFileType, $allowedFileTypes)) {
            $errors['image'] = "Không đúng kiểu dữ liệu: " . $imageFileType . ". Cho phép tệp: " . implode(", ", $allowedFileTypes);
        } elseif (move_uploaded_file($imageTmpName, $targetFile)) {
            if (empty($errors)) {
                // Add check for empty errors
                $sql = "UPDATE banners SET title='$title', content='$content', image_url='$targetFile', sort_order='$sortOrder', active='$active' WHERE id=$id";

                if (mysqli_query($con, $sql)) {
                    $successMessage = "Chỉnh sửa Banner thành công";
                } else {
                    $errorMessage = "Lỗi khi sửa banner: " . mysqli_error($con);
                }
            }
        } else {
            $errorMessage = "Lỗi khi upload ảnh: " . $_FILES['image_url']['error'];
        }
    } else {
        // If no new image is uploaded, update other fields excluding the image field
        // Add check for empty errors
        if (empty($errors)) {
            $sql = "UPDATE banners SET `title`='$title', `content`='$content', `sort_order`='$sortOrder', `active`='$active' WHERE id=$id";

            if (mysqli_query($con, $sql)) {
                $successMessage = "Sửa Banner thành công ";
            } else {
                $errorMessage = "Lỗi khi sửa banner: " . mysqli_error($con);
            }
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
                        <h3>Sửa Banner</h3>
                    </div>

                    <div class="widget-content">
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
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
                                                    <span class="help-inline error"><?php echo ($errors['title']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="content">Content</label>
                                        </td>
                                        <td>
                                            <div class="controls">
                                                <textarea class="span6" rows="5" id="content" name="content"><?php echo $formData['content']; ?></textarea><br>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="image_url">Ảnh</label>
                                        </td>
                                        <td>
                                            <div class="controls">
                                                <img src="<?php echo $formData['imageUrl'] ?? ''; ?>" height="50"><br>
                                                <input type="file" id="image_url" name="image_url"><br>
                                                <?php if (!empty($errors['image'])) : ?>
                                                    <span class="help-inline error"><?php echo ($errors['image']); ?></span>
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
                                                <input type="text" class="span6" id="sort_order" name="sort_order" value="<?php echo $formData['sort_order']; ?>"><br>
                                                <?php if (!empty($errors['sort_order'])) : ?>
                                                    <span class="help-inline error"><?php echo ($errors['sort_order']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="active">Active</label>
                                        </td>
                                        <td>
                                            <div class="controls">
                                                <input type="checkbox" id="active" name="active" <?php if ($formData['active'] ?? false) echo "checked"; ?>>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Update Banner</button>
                                    <a class="btn" href="banner.php">Hủy</a>
                                </div>
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