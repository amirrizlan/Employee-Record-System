<?php
include "config.php";
if (!isset($_SESSION["user_id"])) header("Location: login.php");

$id = intval($_GET["id"]);
$sql = "DELETE FROM employees WHERE id=$id";
mysqli_query($conn, $sql);

header("Location: view_employee.php");
exit;
?>
