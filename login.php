<?php
session_start();
include "koneksi.php";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Gunakan prepared statement untuk keamanan
    $query = "SELECT id, password FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $_SESSION['user_id'] = $row['id'];
    echo "success";
} else {
    echo "error";
}
    
    mysqli_stmt_close($stmt);
} else {
    echo "error";
}
?>