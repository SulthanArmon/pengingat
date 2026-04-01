<?php
include "koneksi.php";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // LANGSUNG ambil password tanpa di-hash
    
    // Cek apakah username sudah ada
    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
    mysqli_stmt_bind_param($check, "s", $username);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);
    
    if (mysqli_stmt_num_rows($check) > 0) {
        echo "exists";
    } else {
        // Insert user baru
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "success";
        } else {
            echo "error";
        }
        
        mysqli_stmt_close($stmt);
    }
    
    mysqli_stmt_close($check);
} else {
    echo "error";
}
?>