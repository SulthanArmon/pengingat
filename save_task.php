<?php
session_start();
include "koneksi.php";

$title=$_POST['title'];
$time=$_POST['time'];
$user_id=$_SESSION['user_id'];

mysqli_query($conn,"INSERT INTO tasks(user_id,title,reminder_time) VALUES('$user_id','$title','$time')");
?>