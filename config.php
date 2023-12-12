<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_1";

// Tạo kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
