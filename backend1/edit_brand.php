<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

$errors = [];
$formData = [];

// Retrieve the brand details from the database and pre-populate the form fields
if (isset($_GET['id'])) {
    $id = trim($_GET['id']);
    $sql = "SELECT * FROM brands WHERE id=$id";
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_array($result)) {
        $formData['name'] = $row['name'];
        $formData['imageUrl'] = $row['image_url'];
        $formData['link'] = $row['link'];
        $formData['sort_order'] = $row['sort_order'];
        $formData['active'] = $row['active'];
    } else {
        $errorMessage = "Brand not found";
    }
}

// Handle form submissions for editing an existing brand
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = trim($_POST['id']);
    $name = trim($_POST['name']);
    $imageName = $_FILES['image_url']['name'];
    $imageTmpName = $_FILES['image_url']['tmp_name'];
    $link = trim($_POST['link']);
    $sortOrder = trim($_POST['sort_order']);
    $active = isset($_POST['active']) ? 1 : 0;

    // Check for errors in name, link, and sort order

    if (empty($name)) {
        $errors['name'] = "Vui lòng nhập nơi xuất sứ.";
    } else {
        $formData['name'] = $name;
    }

    if (empty($link)) {
        $errors['link'] = "Vui lòng nhập link.";
    } else {
        $formData['link'] = $link;
    }

    if (empty($sortOrder) || !is_numeric($sortOrder)) {
        $errors['sort_order'] = "Vui lòng nhập đúng kiểu dữ liệu.";
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
            $errors['image'] = "Không đúng: " . $imageFileType . ". Cho phép tệp: " . implode(", ", $allowedFileTypes);
        } elseif (move_uploaded_file($imageTmpName, $targetFile)) {
            if (empty($errors)) {
                // Add check for empty errors
                $sql = "UPDATE brands SET name='$name', image_url='$targetFile', link='$link', sort_order='$sortOrder', active='$active' WHERE id=$id";

                if (mysqli_query($con, $sql)) {
                    $successMessage = "Sửa xuất sứ thành công";
                } else {
                    $errorMessage = "Lỗi khi sửa xuất sứ: " . mysqli_error($con);
                }
            }
        } else {
            $errorMessage = "Lỗi khi upload file: " . $_FILES['image_url']['error'];
        }
    } else {
        // If no new image is uploaded, update other fields excluding the image field
        // Add check for empty errors
        if (empty($errors)) {
            $sql = "UPDATE brands SET `name`='$name', `link`='$link', `sort_order`='$sortOrder', `active`='$active' WHERE id=$id";

            if (mysqli_query($con, $sql)) {
                $successMessage = "Sửa xuất sứ thành công";
            } else {
                $errorMessage = "Lỗi khi sửa xuất sứ: " . mysqli_error($con);
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
                        <h3>Sửa Brand</h3>
                    </div>

                    <div class="widget-content">
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <fieldset>
                                <table>
                                    <tr>
                                        <td>
                                            <label class="control-label" for="name">Nơi xuất sứ<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <div class="controls">
                                                <input type="text" class="span6" id="name" name="name" value="<?php echo $formData['name']; ?>"><br>
                                                <?php if (!empty($errors['name'])) : ?>
                                                    <span class="help-inline error"><?php echo ($errors['name']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label class="control-label" for="image_url">Ảnh<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <div class="controls">
                                                <img src="<?php echo $formData['imageUrl'] ?? ''; ?>" height="50">
                                                <br>
                                                <input type="file" id="image_url" name="image_url"><br>
                                                <?php if (!empty($errors['image'])) : ?>
                                                    <span class="help-inline error"><?php echo ($errors['image']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label class="control-label" for="link">Link<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <div class="controls">
                                                <input type="text" class="span6" id="link" name="link" value="<?php echo $formData['link']; ?>"><br>
                                                <?php if (!empty($errors['link'])) : ?>
                                                    <span class="help-inline error"><?php echo ($errors['link']); ?></span>
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

                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Update Brand</button>
                                                <a class="btn" href="brands.php">Hủy</a>
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