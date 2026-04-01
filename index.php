<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Pengingat</title>
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
    display: none;
}

.active {
    display: block;
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

/* Link styling - dibuat seperti link pada umumnya */
a {
    color: #0066cc; /* Biru standar link */
    cursor: pointer;
    text-decoration: underline; /* Garis bawah */
}

a:hover {
    color: #004499; /* Biru lebih gelap saat hover */
}

/* Opsional: styling untuk judul */
h2 {
    text-align: center;
    color: #333;
    margin-top: 0;
}
</style>
</head>
<body>

<div class="box active" id="loginBox">
    <h2>Login</h2>
    <input id="loginUser" placeholder="Username">
    <input id="loginPass" type="password" placeholder="Password">
    <button onclick="login()">Login</button>
    <p>Belum punya akun? <a onclick="showRegister()">Register</a></p>
</div>

<div class="box" id="registerBox">
    <h2>Register</h2>
    <input id="regUser" placeholder="Username">
    <input id="regPass" type="password" placeholder="Password">
    <button onclick="register()">Daftar</button>
    <p>Sudah punya akun? <a onclick="showLogin()">Login</a></p>
</div>

<script>
function showRegister() {
    document.getElementById('loginBox').classList.remove('active');
    document.getElementById('registerBox').classList.add('active');
}

function showLogin() {
    document.getElementById('registerBox').classList.remove('active');
    document.getElementById('loginBox').classList.add('active');
}

function login() {
    fetch("login.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "username=" + loginUser.value + "&password=" + loginPass.value
    })
    .then(res => res.text())
    .then(data => {
        if(data.trim() == "success") {
            window.location = "dashboard.php";
        } else {
            alert("Belum punya akun atau nama dan password salah!");
        }
    });
}

function register() {
    fetch("register.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "username=" + regUser.value + "&password=" + regPass.value
    })
    .then(res => res.text())
    .then(data => {
        if(data.trim() == "success") {
            alert("Berhasil daftar!");
            showLogin();
        } else {
            alert("Username sudah ada!");
        }
    });
}
</script>

</body>
</html>