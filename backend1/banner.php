<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

// Delete banner if the delete button was clicked
if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE banners SET active = 2 WHERE id = $id";
    if (mysqli_query($con, $sql)) {
        $successMessage = "Banner deleted successfully";
    } else {
        $errorMessage = "Error deleting banner: " . mysqli_error($con);
    }
}

$sql = "SELECT * FROM banners WHERE active != 2 ORDER BY sort_order";
$result = mysqli_query($con, $sql);
$banners = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div>
    <ul class="container"><a href="add_banner.php" class="btn btn-primary">Add Banner</i></a></ul>
</div>
<div class="container">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Image</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($banners as $banner) { ?>
                <tr>
                    <td><?php echo $banner['id'] ?></td>
                    <td><?php echo $banner['title'] ?></td>
                    <td><?php echo $banner['content'] ?></td>
                    <td><img src="<?php echo $banner['image_url']; ?>" width=100 alt=""></td>
                    <td>
                        <?php if ($banner['active'] == 1) { ?>
                            <span class="icon-ok"></span>
                        <?php } elseif ($banner['active'] == 0) { ?>
                            <span class="icon-remove"></span>
                        <?php } ?>
                    </td>
                    <td>
                        <a class="btn btn-info" href="edit_banner.php?id=<?php echo $banner['id'] ?>">Edit</a>
                        <a class="btn btn-danger" href="banner.php?action=delete&id=<?php echo $banner['id'] ?>" onclick="return confirm('Are you sure you want to delete this banner?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Display success or error message if set -->
    <?php if (isset($successMessage)) { ?>
        <div class="alert alert-success"><?php echo $successMessage ?></div>
    <?php } ?>

    <?php if (isset($errorMessage)) { ?>
        <div class="alert alert-danger"><?php echo $errorMessage ?></div>
    <?php } ?>

</div><!-- /container -->

<div class="footer1">
    <?php include 'inc/footer.php'; ?>
</div>