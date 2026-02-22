<?php
session_start();
require '../app/db.php';


if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();


    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Username atau password salah";
    }
}
?>


<!doctype html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#FFF7ED] flex items-center justify-center h-screen">
    <form method="POST" class="bg-white p-6 rounded-xl shadow w-80">
        <h1 class="text-xl font-bold mb-4 text-center">Admin Login</h1>
        <?php if (!empty($error)): ?>
            <p class="text-red-500 text-sm mb-2 text-center"><?= $error ?></p>
        <?php endif; ?>
        <input name="username" placeholder="Username" class="border p-2 w-full mb-3" required>
        <input type="password" name="password" placeholder="Password" class="border p-2 w-full mb-3" required>
        <button name="login" class="w-full bg-[#C9A66B] text-white py-2 rounded">Login</button>
    </form>
</body>

</html>
<?php
require '../app/db.php';
if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    $q = $pdo->prepare("SELECT * FROM admins WHERE username=?");
    $q->execute([$u]);
    $a = $q->fetch();
    if ($a && password_verify($p, $a['password'])) {
        $_SESSION['admin'] = true;
        header('Location: dashboard.php');
    }
}
?>