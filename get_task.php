<?php
session_start();
include "koneksi.php";

$user_id=$_SESSION['user_id'];
$result=mysqli_query($conn,"SELECT * FROM tasks WHERE user_id='$user_id' ORDER BY reminder_time ASC");

$now = date("Y-m-d H:i:s");

while($row=mysqli_fetch_assoc($result)){

$class = "";

if($row['status']=="done"){
$class="hijau";
}elseif($row['reminder_time'] < $now){
$class="merah";
}elseif(date("Y-m-d",strtotime($row['reminder_time'])) == date("Y-m-d")){
$class="kuning";
}

echo "<li class='$class'>";
echo "<div class='row'>";
echo "<span>".$row['title']." - ".date("d M Y H:i",strtotime($row['reminder_time']))."</span>";
echo "<div>";
echo "<button class='btn-status' onclick='toggleStatus(".$row['id'].")'>Status</button>";
echo "<button class='btn-hapus' onclick='deleteTask(".$row['id'].")'>Hapus</button>";
echo "</div>";
echo "</div>";
echo "</li>";
}
?>