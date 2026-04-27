<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAdminLoggedIn = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">IndicLex</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="upload.php">Upload</a></li>
                <li class="nav-item"><a class="nav-link" href="search.php">Search</a></li>
                <li class="nav-item"><a class="nav-link" href="preferences.php">Preferences</a></li>
                <li class-"nav-item"><a class="nav-link" href="Reports.php">Reports</a></li>

                <?php if ($isAdminLoggedIn): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Admin Login</a></li>
                <?php endif; ?>

                <!-- Theme Toggle -->
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <button class="btn btn-outline-secondary btn-sm" id="toggleThemeBtn">
                        <i id="themeIcon" class="bi"></i>
                    </button>
                </li>

            </ul>
        </div>
    </div>
</nav>