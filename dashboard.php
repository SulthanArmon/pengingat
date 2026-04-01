<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include "koneksi.php";

$user_id = $_SESSION['user_id'];

// Proses simpan task kalau ada POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['time'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    
    $query = "INSERT INTO tasks (user_id, title, reminder_time, status) VALUES ('$user_id', '$title', '$time', 'belum')";
    mysqli_query($conn, $query);
    
    // Redirect ke halaman yang sama untuk refresh
    header("Location: dashboard.php");
    exit();
}

// Ambil data tasks
$result = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id = '$user_id' ORDER BY reminder_time ASC");
$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengingat Tugas</title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: linear-gradient(120deg, #4facfe, #8e44ad);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            width: 700px;
            max-width: 95%;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            font-size: 14px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #4facfe;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: white;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-simpan {
            background: #4facfe;
            width: 100%;
            font-size: 16px;
        }
        
        .btn-simpan:hover {
            background: #3a8fd9;
        }
        
        .btn-status {
            background: #555;
        }
        
        .btn-status:hover {
            background: #444;
        }
        
        .btn-edit {
            background: #f39c12;
        }
        
        .btn-edit:hover {
            background: #e67e22;
        }
        
        .btn-hapus {
            background: #e74c3c;
        }
        
        .btn-hapus:hover {
            background: #c0392b;
        }

        .btn-logout {
            background: #e74c3c;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .btn-logout:hover {
            background: #c0392b;
        }

        .task-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .task-item {
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            border-left: 5px solid transparent;
        }

        .task-item.merah {
            background: #ffebee;
            border-left-color: #f44336;
        }
        
        .task-item.kuning {
            background: #fff8e1;
            border-left-color: #ffc107;
        }
        
        .task-item.hijau {
            background: #e8f5e9;
            border-left-color: #4caf50;
        }

        .task-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .task-info {
            flex: 1;
        }

        .task-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .task-time {
            font-size: 14px;
            color: #666;
        }

        .task-actions {
            display: flex;
            gap: 8px;
        }

        .task-actions a {
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 13px;
        }

        .filter-section {
            display: flex;
            gap: 10px;
            margin: 20px 0;
        }

        .filter-section input {
            flex: 2;
        }

        .filter-section select {
            flex: 1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .empty-message {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📋 Task Manager</h2>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>

        <!-- FORM SIMPAN TASK -->
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="title" placeholder="Nama kegiatan..." required>
            </div>
            <div class="form-group">
                <input type="datetime-local" name="time" required>
            </div>
            <button type="submit" class="btn btn-simpan">➕ Simpan Tugas</button>
        </form>

        <!-- FILTER DAN PENCARIAN -->
        <div class="filter-section">
            <input type="text" id="searchInput" placeholder="Cari tugas..." onkeyup="filterTasks()">
            <select id="filterSelect" onchange="filterTasks()">
                <option value="all">Semua</option>
                <option value="today">Hari Ini</option>
                <option value="week">Minggu Ini</option>
                <option value="done">Selesai</option>
            </select>
        </div>

        <!-- DAFTAR TUGAS -->
        <ul class="task-list" id="taskList">
            <?php if (empty($tasks)): ?>
                <li class="empty-message">Belum ada tugas. Yuk tambah tugas!</li>
            <?php else: ?>
                <?php 
                $now = date("Y-m-d H:i:s");
                foreach ($tasks as $task): 
                    $class = "";
                    if ($task['status'] == "selesai") {
                        $class = "hijau";
                    } elseif ($task['reminder_time'] < $now) {
                        $class = "merah";
                    } elseif (date("Y-m-d", strtotime($task['reminder_time'])) == date("Y-m-d")) {
                        $class = "kuning";
                    }
                    
                    $waktu = date("d M Y H:i", strtotime($task['reminder_time']));
                ?>
                <li class="task-item <?= $class ?>" data-title="<?= strtolower($task['title']) ?>" data-time="<?= $task['reminder_time'] ?>" data-status="<?= $task['status'] ?>">
                    <div class="task-content">
                        <div class="task-info">
                            <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
                            <div class="task-time">⏰ <?= $waktu ?></div>
                        </div>
                        <div class="task-actions">
                            <a href="toggle_status.php?id=<?= $task['id'] ?>" class="btn-status"><?= $task['status'] == 'belum' ? '✅ Selesai' : '↩️ Belum' ?></a>
                            <a href="edit_task.php?id=<?= $task['id'] ?>" class="btn-edit">✏️ Edit</a>
                            <a href="delete_task.php?id=<?= $task['id'] ?>" class="btn-hapus" onclick="return confirm('Yakin mau hapus tugas ini?')">🗑️ Hapus</a>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <script>
        function filterTasks() {
            let search = document.getElementById('searchInput').value.toLowerCase();
            let filter = document.getElementById('filterSelect').value;
            let tasks = document.querySelectorAll('#taskList .task-item');
            let now = new Date();
            
            tasks.forEach(task => {
                let title = task.getAttribute('data-title');
                let taskTime = new Date(task.getAttribute('data-time'));
                let status = task.getAttribute('data-status');
                let show = true;
                
                // Filter pencarian
                if (search && !title.includes(search)) {
                    show = false;
                }
                
                // Filter kategori
                if (filter === 'today') {
                    let today = new Date().toDateString();
                    if (taskTime.toDateString() !== today) {
                        show = false;
                    }
                }
                else if (filter === 'week') {
                    let nextWeek = new Date();
                    nextWeek.setDate(now.getDate() + 7);
                    if (taskTime > nextWeek) {
                        show = false;
                    }
                }
                else if (filter === 'done' && status !== 'selesai') {
                    show = false;
                }
                
                task.style.display = show ? 'block' : 'none';
            });
        }

        // Notifikasi kalau berhasil disimpan
        <?php if (isset($_GET['saved']) && $_GET['saved'] == 1): ?>
        setTimeout(() => {
            alert('✅ Tugas berhasil disimpan!');
        }, 100);
        <?php endif; ?>
        
        // TAMBAHKAN KODE INI DI DALAM TAG <script> yang sudah ada di dashboard.php
// (cari bagian sebelum </body>)

// CEK NOTIFIKASI SAAT HALAMAN DIMUAT
window.onload = function() {
    // Minta izin notifikasi saat pertama kali
    if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
        Notification.requestPermission();
    }
    
    // Cek tugas yang mendekati deadline
    cekNotifikasiTugas();
}

// Fungsi untuk menampilkan notifikasi native
function tampilNotifikasi(judul, pesan, ikon = null) {
    // Cek apakah browser mendukung notifikasi
    if (!("Notification" in window)) {
        alert("Browser ini tidak mendukung notifikasi desktop");
        return;
    }
    
    // Cek izin notifikasi
    if (Notification.permission === 'granted') {
        // Buat notifikasi
        const options = {
            body: pesan,
            icon: ikon || 'https://via.placeholder.com/128/4facfe/ffffff?text=Task',
            badge: 'https://via.placeholder.com/128/4facfe/ffffff?text=Task',
            vibrate: [200, 100, 200],
            requireInteraction: true,
            tag: 'task-reminder-' + Date.now()
        };
        
        return new Notification(judul, options);
    } else if (Notification.permission !== 'denied') {
        // Minta izin dulu
        Notification.requestPermission().then(function(permission) {
            if (permission === 'granted') {
                const options = {
                    body: pesan,
                    icon: ikon || 'https://via.placeholder.com/128/4facfe/ffffff?text=Task'
                };
                return new Notification(judul, options);
            }
        });
    }
}

// Cek tugas yang perlu notifikasi
function cekNotifikasiTugas() {
    let tasks = document.querySelectorAll('.task-item');
    let now = new Date();
    
    // Ambil data notifikasi yang sudah pernah ditampilkan
    let notifSudah = JSON.parse(localStorage.getItem('notifikasiNative') || '{}');
    let today = new Date().toDateString();
    
    tasks.forEach(task => {
        // Skip tugas yang sudah selesai
        if (task.getAttribute('data-status') === 'selesai') return;
        
        let taskTime = new Date(task.getAttribute('data-time'));
        let taskId = task.querySelector('.btn-status').getAttribute('href').match(/id=(\d+)/)[1];
        let taskTitle = task.querySelector('.task-title').textContent;
        
        // Hitung selisih waktu
        let diffMs = taskTime - now;
        let diffMenit = Math.round(diffMs / (1000 * 60));
        
        // NOTIFIKASI 1: Tugas sudah lewat
        if (diffMs < 0 && !notifSudah[`lewat_${taskId}_${today}`]) {
            tampilNotifikasi(
                '🔴 Tugas Terlewat!', 
                `"${taskTitle}" sudah melewati deadline`
            );
            
            // Tandai sudah ditampilkan hari ini
            notifSudah[`lewat_${taskId}_${today}`] = true;
        }
        
        // NOTIFIKASI 2: Tugas dalam 30 menit
        else if (diffMenit > 0 && diffMenit <= 30 && !notifSudah[`mendekat_${taskId}_${today}`]) {
            tampilNotifikasi(
                '⚠️ Tugas Mendekati Deadline', 
                `"${taskTitle}" akan tiba dalam ${diffMenit} menit`
            );
            
            notifSudah[`mendekat_${taskId}_${today}`] = true;
        }
        
        // NOTIFIKASI 3: Tugas hari ini
        else if (taskTime.toDateString() === now.toDateString() && 
                 diffMenit > 30 && 
                 !notifSudah[`hariini_${taskId}_${today}`]) {
            let jam = taskTime.getHours().toString().padStart(2,'0');
            let menit = taskTime.getMinutes().toString().padStart(2,'0');
            
            tampilNotifikasi(
                '📅 Tugas Hari Ini', 
                `"${taskTitle}" jam ${jam}:${menit}`
            );
            
            notifSudah[`hariini_${taskId}_${today}`] = true;
        }
    });
    
    // Simpan status notifikasi
    localStorage.setItem('notifikasiNative', JSON.stringify(notifSudah));
}

// Cek notifikasi setiap 5 menit
setInterval(cekNotifikasiTugas, 300000);

// Reset notifikasi setiap hari baru
setInterval(function() {
    let now = new Date();
    if (now.getHours() === 0 && now.getMinutes() === 0) {
        // Reset hanya untuk notifikasi yang basadya per hari
        let notifSudah = JSON.parse(localStorage.getItem('notifikasiNative') || '{}');
        let today = now.toDateString();
        
        // Hapus data lama (lebih dari 7 hari)
        for (let key in notifSudah) {
            if (!key.includes(today)) {
                delete notifSudah[key];
            }
        }
        
        localStorage.setItem('notifikasiNative', JSON.stringify(notifSudah));
    }
}, 60000); // Cek setiap menit untuk reset tengah malam
    </script>
</body>
</html>