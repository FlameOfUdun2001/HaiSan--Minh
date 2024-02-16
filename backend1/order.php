<?php
include 'inc/session_login.php';
include 'config.php';
include 'inc/heading.php';
include 'inc/header.php';

$itemsPerPage = 10; // Number of items to display per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$sql = "SELECT * FROM orders";
$result = $con->query($sql);
$totalOrders = $result->num_rows;
$totalPages = ceil($totalOrders / $itemsPerPage);

// Calculate the offset
$offset = ($page - 1) * $itemsPerPage;
$sql = "SELECT * FROM orders LIMIT $itemsPerPage OFFSET $offset";
$result = $con->query($sql);
?>

<div class="container">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Sản phẩm</th>
            <th>Loại thanh toán</th>
            <th>Số lượng</th>
            <th>Tổng tiền</th>
            <th>Tổng sản phẩm</th>
            <th>Ngày mua</th>
            <th>Mã khách hàng</th>
        </tr>
        </thead>
        <tbody>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['customer_name'] . "</td>";
                echo "<td>" . $row['customer_phone'] . "</td>";
                echo "<td>" . $row['customer_email'] . "</td>";
                echo "<td>" . $row['total_money'] . "</td>";
                echo "<td>" . $row['total_products'] . "</td>";
                echo "<td>" . $row['created_date'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "No orders found.";
        }
        ?>

        </tbody>
    </table>

    <div class="pagination">
        <?php
        if ($totalPages > 1) {
            if ($page > 1) {
                echo "<a href='order.php?page=1'>Đầu</a>";
                echo "<a href='order.php?page=" . ($page - 1) .'>Trước</a>';
            }

            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<a href='order.php?page=$i'>$i</a>";
            }

            if ($page < $totalPages) {
                echo "<a href='order.php?page=" . ($page + 1) . '>Sau</a>';
                echo "<a href='order.php?page=$totalPages'>Cuối</a>";
            }
        }
        ?>
    </div>
</div>

<div class="footer1">
    <?php include 'inc/footer.php'; ?>
</div>