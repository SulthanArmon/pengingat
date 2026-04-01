<?php
$host = "localhost";
$user = "penginga_sulthan";
$pass = "#1Sulth4n";
$db = "penginga_pengingat";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset ke UTF-8
mysqli_set_charset($conn, "utf8");
?>