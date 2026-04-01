<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    $stmt = mysqli_prepare($conn, "DELETE FROM tasks WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: dashboard.php");
exit();
?>