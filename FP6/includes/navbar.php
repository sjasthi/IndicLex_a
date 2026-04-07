<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAdminLoggedIn = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">IndicLex</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="catalog.php">Catalog</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="upload.php">Upload</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="search.php">Search</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="preferences.php">Preferences</a>
                </li>

                <?php if ($isAdminLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Admin Login</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <button class="btn btn-outline-light btn-sm ms-3" onclick="toggleTheme()">
                        Toggle Theme
                    </button>
                </li>

            </ul>
        </div>
    </div>
</nav>