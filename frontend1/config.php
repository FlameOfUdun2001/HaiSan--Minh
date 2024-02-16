<?php
// Connect to the database
$con = mysqli_connect("localhost", "root", "", "sell_fishs");

// Check if the connection was successful
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
