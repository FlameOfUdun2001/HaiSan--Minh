<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

if (isset($_POST['submit'])) {
    $productID = $_POST['product_id'];

    // Check product ID already has an image
    $checkQuery = "SELECT COUNT(*) AS count FROM product_images WHERE product_id = '$productID'";
    $checkResult = mysqli_query($con, $checkQuery);
    $checkData = mysqli_fetch_assoc($checkResult);
    $imageCount = $checkData['count'];

    if ($imageCount > 0) {
        // Update the existing image
        $imageURL = $_FILES['image']['name'];
        $targetDirectory = "img/";
        $targetPath = $targetDirectory . basename($imageURL);
        $sortOrder = $_POST['sort_order'];
        $active = 1; // base

        $updateQuery = "UPDATE product_images SET image_url = '$imageURL', sort_order = '$sortOrder', active = '$active' WHERE product_id = '$productID'";
        mysqli_query($con, $updateQuery);

        // Upload the new image file
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);

        // Update the image URL in the products table
        $updateProductQuery = "UPDATE products SET image = '$imageURL' WHERE id = '$productID'";
        mysqli_query($con, $updateProductQuery);

        $successMessage = "Update image successfully";
    } else {
        // Add a new image
        $imageURL = $_FILES['image']['name'];
        $targetDirectory = "img/";
        $targetPath = $targetDirectory . basename($imageURL);
        $sortOrder = $_POST['sort_order'];
        $active = 1; // base active

        // Insert new image
        $insertQuery = "INSERT INTO product_images (product_id, image_url, sort_order, active) VALUES ('$productID', '$imageURL', '$sortOrder', '$active')";
        if (mysqli_query($con, $insertQuery)) {
            // Upload the image file
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);

            // Update the image URL in the products table
            $updateProductQuery = "UPDATE products SET image = '$imageURL' WHERE id = '$productID'";
            mysqli_query($con, $updateProductQuery);

            $successMessage = "Add image successfully";
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}

mysqli_close($con);
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
                        <h3>Edit User</h3>
                    </div>
                    <div class="widget-content" enctype="multipart/form-data">
                        <h2>Add image</h2>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="control-group ">
                                <label class="control-label" for="name">Id product<span class="red"> *</span></label>
                                <div class="controls">
                                    <input type="text" name="product_id" id="product_id" required>
                                </div>
                            </div>


                            <label for="image">Image<span class="red"> *</span></label>
                            <input type="file" name="image" id="image" required>

                            <label for="sort_order">Sort order<span class="red"> *</span></label>
                            <input type="text" name="sort_order" id="sort_order" required>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="submit">Add image</button>
                                <a class="btn" href="users.php">Cancel</a>
                            </div>
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