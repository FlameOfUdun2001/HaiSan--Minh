<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

$errors = [];
$formData = [];

// Retrieve the product details from the database and pre-populate the form fields
if (isset($_GET['id'])) {
    $id = trim($_GET['id']);
    $sql = "SELECT * FROM products WHERE id=$id";
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_array($result)) {
        $formData['name'] = $row['name'];
        $formData['description'] = $row['description'];
        $formData['price'] = $row['price'];
        $formData['old_price'] = $row['old_price'];
        $formData['brand_id'] = $row['brand_id'];
        $formData['image'] = $row['image'];
        $formData['category_id'] = $row['category_id'];
        $formData['tags'] = $row['tags'];
        $formData['is_best_sell'] = $row['is_best_sell'];
        $formData['is_new'] = $row['is_new'];
        $formData['sort_order'] = $row['sort_order'];
        $formData['active'] = $row['active'];
    } else {
        $errorMessage = "Product not found";
    }
}


// Handle form submissions for editing an existing product
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = trim($_POST['id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $oldPrice = trim($_POST['old_price']);;
    $brandId = trim($_POST['brand_id']);
    $categoryId = trim($_POST['category_id']);
    $tags = trim($_POST['tags']);
    $bestSell = isset($_POST['is_best_sell']) ? 1 : 0;
    $isNew = isset($_POST['is_new']) ? 1 : 0;
    $sortOrder = trim($_POST['sort_order']);;
    $active = isset($_POST['active']) ? 1 : 0;

    // Check for errors in name, description, price, old price, brand ID, category ID, and sort order

    if (empty($name)) {
        $errors['name'] = "Vui lòng nhập tên sản phẩm.";
    } else {
        $formData['name'] = $name;
    }

    if (empty($description)) {
        $errors['description'] = "Vui lòng nhập miêu tả.";
    } else {
        $formData['description'] = $description;
    }

    if (empty($price) || !is_numeric($price)) {
        $errors['price'] = "Vui lòng nhập giá tiền.";
    } else {
        $formData['price'] = $price;
    }

    if (!empty($oldPrice) && !is_numeric($oldPrice)) {
        $errors['old_price'] = "Vui lòng nhập giá tiền cũ.";
    } else {
        $formData['old_price'] = $oldPrice;
    }

    if (empty($brandId) || !is_numeric($brandId)) {
        $errors['brand_id'] = "Vui lòng chọn xuất sứ.";
    } else {
        $formData['brand_id'] = $brandId;
    }

    if (empty($categoryId) || !is_numeric($categoryId)) {
        $errors['category_id'] = "Vui lòng chọn danh mục.";
    } else {
        $formData['category_id'] = $categoryId;
    }

    if (empty($sortOrder) || !is_numeric($sortOrder)) {
        $errors['sort_order'] = "Vui lòng nhập Thứ tự sắp xếp.";
    } else {
        $formData['sort_order'] = $sortOrder;
    }

    if (empty($errors)) {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $sql = "UPDATE products SET `name`='$name', `description`='$description', `price`='$price', `old_price`='$oldPrice', `brand_id`='$brandId', `category_id`='$categoryId', `tags`='$tags', `is_best_sell`='$bestSell', `is_new`='$isNew', `sort_order`='$sortOrder', `active`='$active' WHERE `id`=$id";
        } else {
            // Handle image upload
            $imagePath = null;
            $tmpFilePath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileError = $_FILES['image']['error'];

            $uploadDir = 'img/';
            $uniqueFileName = uniqid() . '_' . $fileName;
            $imagePath = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($tmpFilePath, $imagePath)) {
                $sql = "UPDATE products SET `name`='$name', `description`='$description', `price`='$price', `old_price`='$oldPrice', `brand_id`='$brandId', `category_id`='$categoryId', `tags`='$tags', `is_best_sell`='$bestSell', `is_new`='$isNew', `sort_order`='$sortOrder', `active`='$active', `image`='$imagePath' WHERE `id`=$id";

                // Update product_images table
                $imageSql = "UPDATE product_images SET `image_url`='$fileName' WHERE `product_id`='$id'";
                mysqli_query($con, $imageSql);
            } else {
                $errors['image'] = "Lỗi thêm ảnh.";
            }
        }

        if (empty($errors)) {
            if (mysqli_query($con, $sql)) {
                $successMessage = "Sửa sản phẩm thành công";
            } else {
                $errorMessage = "Lỗi khi sửa sản phẩm: " . mysqli_error($con);
            }
        }
    }
}

// Get brands and categories
$brandsSql = 'SELECT * FROM brands WhERE active != 2 ORDER BY sort_order';
$brandsResult = mysqli_query($con, $brandsSql);
$brands = mysqli_fetch_all($brandsResult, MYSQLI_ASSOC);

$categoriesSql = 'SELECT * FROM categories WHERE active != 2 ORDER BY sort_order';
$categoriesResult = mysqli_query($con, $categoriesSql);
$categories = mysqli_fetch_all($categoriesResult, MYSQLI_ASSOC);
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
                        <h3>Sửa sản phẩm</h3>
                    </div>
                    <div class="widget-content">
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <fieldset>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="brand_id">Xuất sứ<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <select class="form-control " id="brand_id" name="brand_id">
                                                <?php foreach ($brands as $brand) : ?>
                                                    <option value="<?php echo $brand['id']; ?>" <?php echo $formData['brand_id'] == $brand['id'] ? 'selected' : ''; ?>><?php echo $brand['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <label for="category_id">Danh mục<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <select class="form-control " id="category_id" name="category_id">
                                                <?php foreach ($categories as $category) : ?>
                                                    <option value="<?php echo $category['id']; ?>" <?php echo $formData['category_id'] == $category['id'] ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label class="control-label" for="name">Tên sản phẩm<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <input type="text" id="name" name="name" value="<?php echo ($formData['name'] ?? ''); ?>"><br>
                                            <?php if (!empty($errors['name'])) : ?>
                                                <span class="help-inline error"><?php echo ($errors['name']); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <label for="sort_order">Thứ tự sắp xếp<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <input type="text" class=" form-control" id="sort_order" name="sort_order" value="<?php echo $formData['sort_order']; ?>"><br>
                                            <?php if (!empty($errors['sort_order'])) : ?>
                                                <span class="help-inline error"><?php echo ($errors['sort_order']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="price">Giá tiền<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <input type="number" class=" form-control" id="price" name="price" min="0" value="<?php echo $formData['price']; ?>"><br>
                                            <?php if (!empty($errors['price'])) : ?>
                                                <span class="help-inline error"><?php echo ($errors['price']); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <label for="old_price">Giá tiền cũ</label>
                                        </td>
                                        <td>
                                            <input type="number" class=" form-control" id="old_price" name="old_price" min="0" value="<?php echo $formData['old_price']; ?>"><br>
                                            <?php if (!empty($errors['old_price'])) : ?>
                                                <span class="help-inline error"><?php echo ($errors['old_price']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <td>
                                        <label class="control-label" for="image">Ảnh<span class="red"> *</span></label>
                                    </td>
                                    <td>
                                        <div class="controls">
                                            <img src="<?php echo $formData['image'] ?? ''; ?>" width="220" alt="Product Image">
                                            <br>
                                            <input type="file" id="image" name="image" accept="image/*">
                                            <?php if (!empty($errors['image'])) : ?>
                                                <span class="help-inline error"><?php echo ($errors['image']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <tr>
                                        <td>
                                            <label for="tags">Tags</label>
                                        </td>
                                        <td colspan="3">
                                            <input type="text" class=" form-control span7" id="tags" name="tags" value="<?php echo $formData['tags']; ?>">
                                            <?php if (!empty($errors['tags'])) : ?>
                                                <span class="help-inline error"><?php echo ($errors['tags']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="description">Mô tả<span class="red"> *</span></label>
                                        </td>
                                        <td colspan="3">
                                            <textarea class=" form-control span7" id="description" name="description"><?php echo $formData['description']; ?></textarea><br>
                                            <?php if (!empty($errors['description'])) : ?>
                                                <span class="help-inline error"><?php echo ($errors['description']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Bán chạy <input type="checkbox" class="form-check-input" id="is_best_sell" name="is_best_sell" <?php echo isset($formData['is_best_sell']) && $formData['is_best_sell'] ? 'checked' : ''; ?>>
                                            Mới nhất <input type="checkbox" class="form-check-input" id="is_new" name="is_new" <?php echo isset($formData['is_new']) && $formData['is_new'] ? 'checked' : ''; ?>>
                                            Active <input type="checkbox" class="form-check-input" id="active" name="active" <?php echo isset($formData['active']) && $formData['active'] ? 'checked' : ''; ?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td colspan="3">
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Lưu</button>
                                                <a href="index.php" class="btn">Hủy</a>
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