<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

$itemsPerPage = 10; // Number of users to display per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the total number of users
$sqlTotal = "SELECT COUNT(id) as total FROM users";
$resultTotal = mysqli_query($con, $sqlTotal);
$totalUsers = mysqli_fetch_assoc($resultTotal)['total'];
$totalPages = ceil($totalUsers / $itemsPerPage);

// Calculate the offset
$offset = ($page - 1) * $itemsPerPage;

// Retrieve users for the current page
$sql = "SELECT * FROM users LIMIT $itemsPerPage OFFSET $offset";
$result = mysqli_query($con, $sql);
?>

<div class="container">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $user['id'] ?></td>
                    <td><?php echo $user['username'] ?></td>
                    <td><?php echo $user['email'] ?></td>
                    <td><?php echo $user['token'] ?></td>
                    <td>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="users.php?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Pagination buttons -->
    <div class="pagination">
        <?php
        if ($totalPages > 1) {
            if ($page > 1) {
                echo "<a href='users.php?page=1'>First</a>";
                echo "<a href='users.php?page=" . ($page - 1) . '>Previous</a>';
            }

            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<a href='users.php?page=$i'>$i</a>";
            }

            if ($page < $totalPages) {
                echo "<a href='users.php?page=" . ($page + 1) . '>Next</a>';
                echo "<a href='users.php?page=$totalPages'>Last</a>";
            }
        }
        ?>
    </div>
</div>

<div class="footer1">
    <?php include 'inc/footer.php'; ?>
</div>