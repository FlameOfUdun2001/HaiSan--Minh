<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';


$formData = [
    'name' => '',
    'description' => '',
    'price' => '',
    'old_price' => null,
    'brand_id' => '',
    'category_id' => '',
    'tags' => null,
    'is_best_sell' => false,
    'is_new' => false,
    'sort_order' => null,
    'active' => false,
    'image' => ''
];

$errors = [];




if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $oldPrice = isset($_POST['old_price']) ? (float)$_POST['old_price'] : null;
    $brandId = trim($_POST['brand_id']);
    $categoryId = trim($_POST['category_id']);
    $tags = isset($_POST['tags']) ? $_POST['tags'] : null;
    $bestSell = isset($_POST['is_best_sell']) ? 1 : 0;
    $isNew = isset($_POST['is_new']) ? 1 : 0;
    $sortOrder = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : null;
    $active = isset($_POST['active']) ? 1 : 0;

    if (empty($name)) {
        $errors['name'] = "Vui lòng nhập tên hải sản.";
    } else {
        $formData['name'] = $name;
    }

    if (empty($description)) {
        $errors['description'] = "Vui lòng nhập mô tả.";
    } else {
        $formData['description'] = $description;
    }

    if (empty($price) || !is_numeric($price)) {
        $errors['price'] = "Vui lòng nhập giá tiền.";
    } else {
        $formData['price'] = $price;
    }

    if (empty($brandId)) {
        $errors['brand'] = "Vui lòng chọn xuất sứ.";
    } else {
        $formData['brand_id'] = $brandId;
    }

    if (empty($categoryId)) {
        $errors['category'] = "Vui lòng chọn danh mục.";
    } else {
        $formData['category_id'] = $categoryId;
    }

    $imagesPath = [];
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
    $imageErrors = $_FILES['image']['error'];
    $imageCount = count($_FILES['image']['name']);

        for ($i = 0; $i < $imageCount; $i++) {
            if ($imageErrors[$i] === UPLOAD_ERR_OK) {
                $tmpFilePath = $_FILES['image']['tmp_name'][$i];
                $fileName = $_FILES['image']['name'][$i];
                $fileSize = $_FILES['image']['size'][$i];
                $fileType = $_FILES['image']['type'][$i];

                $imagePath = $fileName;

                if (move_uploaded_file($tmpFilePath, $imagePath)) {
                    $imagesPath[] = $imagePath;
                } else {
                    $errors['image'] = "Lỗi khi thêm ảnh.";
                }
            }
        }

        if (empty($formData['image']) && !empty($imagesPath)) {
            $formData['image'] = $imagesPath[0];
        }
    }

    if (empty($formData['image'])) {
        $errors['image'] = "Trống ảnh.";
    }

    if (empty($errors)) {
        // Insert into products table
        $sql = "INSERT INTO products (`name`, `description`, `price`, `old_price`, `brand_id`, `category_id`, `tags`, `is_best_sell`, `is_new`, `sort_order`, `active`, `image`)
                VALUES ('$name', '$description', '$price', '$oldPrice', '$brandId', '$categoryId', '$tags', '$bestSell', '$isNew', '$sortOrder', '$active', '$formData[image]')";
        // Insert into products table
        if (mysqli_query($con, $sql)) {
            $productId = mysqli_insert_id($con); // Get the id of the inserted record
            $successMessage = 'Thêm sản phẩm thành công';

            // Insert into product_images table
            if (!empty($imagesPath)) {
                $sortOrder = 1;
                foreach ($imagesPath as $key => $imagePath) {
                    if ($key === 0) {
                        $imageSql = "INSERT INTO product_images (`product_id`, `image_url`, `sort_order`, `active`)
                                    VALUES ('$productId', '$imagePath', 1, 1)";
                    } else {
                        $sortOrder++;
                        $imageSql = "INSERT INTO product_images (`product_id`, `image_url`, `sort_order`, `active`)
                                    VALUES ('$productId', '$imagePath', $sortOrder, 1)";
                    }

                    if (mysqli_query($con, $imageSql)) {
                        $sortOrder++;
                    } else {
                        $errorMessage = 'Lỗi khi thêm ảnh: ' . mysqli_error($con);
                    }
                }
            }
        } else {
            $errorMessage = 'Lỗi khi thêm sản phẩm : ' . mysqli_error($con);
        }
    }
}

// Get the list of brands and categories
$brandsSql = 'SELECT * FROM brands WHERE active != 2 ORDER BY sort_order';
$brandsResult = mysqli_query($con, $brandsSql);
$brands = mysqli_fetch_all($brandsResult, MYSQLI_ASSOC);

$categoriesSql = 'SELECT * FROM categories WHERE active != 2 ORDER BY sort_order';
$categoriesResult = mysqli_query($con, $categoriesSql);
$categories = mysqli_fetch_all($categoriesResult, MYSQLI_ASSOC);

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
                <div class="widget">
                    <div class="widget-header">
                        <i class="icon-edit"></i>
                        <h3>Thêm Hải Sản</h3>
                    </div>
                    <div class="widget-content">
                        <form method="post" enctype="multipart/form-data">
                            <fieldset>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="brand_id">Xuất sứ<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <select class="form-control " id="brand_id" name="brand_id">
                                                <option value="">Chọn nơi xuất sứ</option>
                                                <?php foreach ($brands as $brand) : ?>
                                                    <?php $selected = $brand['id'] == $formData['brand_id'] ? 'selected' : ''; ?>
                                                    <option value="<?php echo $brand['id']; ?>" <?php echo $selected; ?>><?php echo $brand['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select><br>
                                            <?php if (isset($errors['brand'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['brand']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <label for="category_id">Danh mục<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <select class="form-control" id="category_id" name="category_id">
                                                <option value="">Chọn danh mục</option>
                                                <?php foreach ($categories as $category) : ?>
                                                    <?php $selected = $category['id'] == $formData['category_id'] ? 'selected' : ''; ?>
                                                    <option value="<?php echo $category['id']; ?>" <?php echo $selected; ?>><?php echo $category['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select><br>
                                            <?php if (isset($errors['category'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['category']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="name">Tên sản phẩm<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <input type="text" id="name" name="name" value="<?php echo $formData['name']; ?>"><br>
                                            <?php if (isset($errors['name'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['name']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <label for="sort_order">Thứ tự sắp xếp</label>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="<?php echo $formData['sort_order']; ?>"><br>
                                            <?php if (isset($errors['sort_order'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['sort_order']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="price">Gía<span class="red"> *</span></label>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="price" name="price" min="0" value="<?php echo number_format($formData['price'], 2, '.', ','); ?>"><br>
                                            <?php if (isset($errors['price'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['price']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <label for="old_price">Giá cũ</label>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="old_price" name="old_price" min="0" value="<?php echo $formData['old_price']; ?>"><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="image">Ảnh</label>
                                        </td>
                                        <td colspan="3">
                                        <input type="file" class="form-control" id="image" name="image[]" multiple onchange="previewImages(event)">
                                        <table>
                                        <tr>
                                            <td>Ảnh chính</td>
                                        </tr>
                                        <tr>
                                            <td id="main-image"></td>
                                        </tr>
                                        </table>
                                            <?php if (isset($errors['image'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['image']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="tags">Tags:</label>
                                        </td>
                                        <td colspan="3">
                                            <input id="myTags" type="text" class="form-control span7" id="tags" name="tags" value="<?php echo $formData['tags']; ?>" multiple><br>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="description">Miêu tả<span class="red"> *</span></label>
                                        </td>
                                        <td colspan="3">
                                            <textarea class="form-control span7" id="description" name="description"><?php echo $formData['description']; ?></textarea><br>
                                            <?php if (isset($errors['description'])) : ?>
                                                <span class="help-inline error"><?php echo $errors['description']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Bán chạy <input type="checkbox" class="form-check-input span1 " id="is_best_sell" name="is_best_sell" <?php if ($formData['is_best_sell']) echo 'checked'; ?>>
                                            Mới nhất <input type="checkbox" class="form-check-input span1" id="is_new" name="is_new" <?php if ($formData['is_new']) echo 'checked'; ?>>
                                            Active <input type="checkbox" class="form-check-input span1" id="active" name="active" <?php if ($formData['active']) echo 'checked'; ?>>
                                        </td>
                                    </tr>
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