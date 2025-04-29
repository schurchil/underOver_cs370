<?php
$host = "127.0.0.1";
$username = "root";
$password = "Stephanie";
$database = "bankingproject";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}
?>
