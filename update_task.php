<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['time'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $user_id = $_SESSION['user_id'];
    
    $stmt = mysqli_prepare($conn, "UPDATE tasks SET title = ?, reminder_time = ? WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ssii", $title, $time, $id, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard.php?status=updated");
    } else {
        header("Location: dashboard.php?status=error");
    }
    
    mysqli_stmt_close($stmt);
} else {
    header("Location: dashboard.php");
}
?>