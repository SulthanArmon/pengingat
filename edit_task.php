<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include "koneksi.php";

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil data task
$stmt = mysqli_prepare($conn, "SELECT * FROM tasks WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$task = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$task) {
    header("Location: dashboard.php");
    exit();
}
?>
<!doctype html>
<html>
<head>
    <title>Edit Tugas</title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: linear-gradient(120deg, #4facfe, #8e44ad);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        input, button {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            background: #4facfe;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #3a8fd9;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .cancel {
            background: #dc3545;
        }
        .cancel:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Edit Tugas</h2>
        <form method="POST" action="update_task.php">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">
            <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
            <input type="datetime-local" name="time" value="<?= date('Y-m-d\TH:i', strtotime($task['reminder_time'])) ?>" required>
            <button type="submit">Update</button>
            <a href="dashboard.php"><button type="button" class="cancel">Batal</button></a>
        </form>
    </div>
</body>
</html>