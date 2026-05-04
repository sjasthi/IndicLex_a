<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/includes/db_mysqli.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $sql = "SELECT user_id, username, password_hash, role, is_active FROM users WHERE username = '$username' AND password_hash = '$password'";
    $result = $conn->query($sql);


    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if ((int)$user['is_active'] !== 1) {
            $error = "Account inactive.";
        }
       // elseif (!password_verify($password, $user['password_hash'])) {
       //     $error = "Invalid username or password.";
       // } 
        elseif ($user['role'] !== 'admin') {
            $error = "Not authorized.";
        } else {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit;
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<?php include 'includes/navbar.php'; ?>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow-sm mx-auto" style="max-width: 400px;">
        <h3 class="text-center">Admin Login</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-3">
            <input class="form-control mb-3" name="username" placeholder="Username" required>
            <input type="password" class="form-control mb-3" name="password" placeholder="Password" required>
            <button class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>
</body>
<?php include 'includes/footer.php'; ?>
</html>
