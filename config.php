<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "employee_records");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
