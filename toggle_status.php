<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    // Ambil status saat ini
    $stmt = mysqli_prepare($conn, "SELECT status FROM tasks WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $new_status = ($row['status'] == 'belum') ? 'selesai' : 'belum';
        
        // Update status
        $update = mysqli_prepare($conn, "UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($update, "sii", $new_status, $id, $user_id);
        mysqli_stmt_execute($update);
        mysqli_stmt_close($update);
    }
    
    mysqli_stmt_close($stmt);
}

// Redirect kembali ke dashboard
header("Location: dashboard.php");
exit();
?>