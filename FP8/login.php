<?php
// show PHP errors while testing 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/includes/db_mysqli.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // get the username and password from the form
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // prepare SQL query to find the user by username
    $stmt = $conn->prepare("
        SELECT user_id, username, password_hash, role, is_active
        FROM users
        WHERE username = ?
        LIMIT 1
    ");

    // bind the username into the ? in the query
    // "s" means the value being inserted is a string
    $stmt->bind_param("s", $username);

    // run the query
    $stmt->execute();

    // get result 
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        
        $user = $result->fetch_assoc();

        // check if the account is active in the database
        if ((int)$user['is_active'] !== 1) {
            $error = "Account inactive.";

        // this checks the password against the hashed password in the table
        } elseif (!password_verify($password, $user['password_hash'])) {
            $error = "Invalid username or password.";

        } elseif ($user['role'] !== 'admin') {
            $error = "Not authorized.";

        } else {
            // if everything is correct, store user info in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // send admin to dashboard after successful login
            header("Location: dashboard.php");
            exit;
        }
    } else {
        // if username was not found
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

<body class="bg-light">

<?php
include 'includes/navbar.php';
?>

<div class="container mt-5">
    <div class="card p-4 shadow-sm mx-auto" style="max-width: 400px;">
        <h3 class="text-center">Admin Login</h3>

        <?php if ($error): ?>
            <!-- show error message if login fails -->
            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-3">
            <input class="form-control mb-3" name="username" placeholder="Username" required>
            <input type="password" class="form-control mb-3" name="password" placeholder="Password" required>
            <button class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>

<?php
// include footer at the bottom of the page
include 'includes/footer.php';
?>

</body>
</html>