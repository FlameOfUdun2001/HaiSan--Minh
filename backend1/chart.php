<?php
include 'inc/session_login.php';
include 'inc/heading.php';
include 'inc/header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sell_fishs";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Calculate the date for 30 days ago
$thirtyDaysAgo = date("Y-m-d", strtotime("-30 days"));

// Generate a date range for the last 30 days
$dateRange = array();
$currentDate = date("Y-m-d");
while ($currentDate >= $thirtyDaysAgo) {
    $dateRange[] = $currentDate;
    $currentDate = date("Y-m-d", strtotime("-1 day", strtotime($currentDate)));
}
$dateRange = array_reverse($dateRange);

// Query the revenue data
$sql = "SELECT DATE_FORMAT(created_date, '%Y-%m-%d') AS day, SUM(total_money) AS revenue
        FROM orders
        WHERE status = 1 AND created_date >= '$thirtyDaysAgo'
        GROUP BY day";
$result = $conn->query($sql);

// Create an associative array to store revenue data
$revenueData = array();
while ($row = $result->fetch_assoc()) {
    $revenueData[$row['day']] = $row['revenue'];
}

// Populate the revenue data for the entire date range
$completeData = array();
foreach ($dateRange as $day) {
    $revenue = isset($revenueData[$day]) ? $revenueData[$day] : 0;
    $completeData[] = array('day' => $day, 'revenue' => $revenue);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doanh thu trong 30 ngày</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="revenueChart"></canvas>
    <script>
        // Get the complete 30-day revenue data from PHP
        var chartData = <?php echo json_encode($completeData); ?>;

        // Prepare the data for the chart
        var labels = chartData.map(function(item) {
            return item.day;
        });
        var data = chartData.map(function(item) {
            return item.revenue;
        });

        // Create the chart using Chart.js
        var ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '30-Day Revenue',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <div class="footer1">
        <?php include 'inc/footer.php'; ?>
    </div>
</body>
</html>
