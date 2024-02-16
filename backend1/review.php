<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

// Get all reviews from the database
$sql = "SELECT * FROM reviews ORDER BY name";
$result = mysqli_query($con, $sql);
$reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


<div class="container">
    <table class="table custom-table table-striped table-bordered">
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Name</th>
                <th>Email</th>
                <th>Product Name</th>
                <th>Review</th>
                <th>Rating</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review) { ?>
                <tr>
                    <!-- <td id="review_id"><?php echo $review['review_id']; ?></td> -->
                    <td id="name"><?php echo $review['name']; ?></td>
                    <td id="email"><?php echo $review['email']; ?></td>
                    <td id="productname"><?php echo $review['productname']; ?></td>
                    <td id="review"><?php echo $review['review']; ?></td>
                    <td id="rating"><?php echo $review['rating']; ?></td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="footer1">
    <?php include 'inc/footer.php'; ?>
</div>