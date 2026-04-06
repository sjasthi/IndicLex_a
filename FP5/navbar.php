<?php
$activePage = basename($_SERVER['PHP_SELF'], ".php");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">IndicLex</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link <?php if ($activePage == 'index') { echo "active"; } ?>" href="index.php">Home</a>
                </li>

                <!-- Upload -->
                <li class="nav-item">
                    <a class="nav-link <?php if ($activePage == 'upload') { echo "active"; } ?>" href="upload.php">Upload</a>
                </li>

                <!-- Search -->
                <li class="nav-item">
                    <a class="nav-link <?php if ($activePage == 'search') { echo "active"; } ?>" href="search.php">Search</a>
                </li>

                <!-- Preferences -->
                <li class="nav-item">
                    <a class="nav-link <?php if ($activePage == 'preferences') { echo "active"; } ?>" href="preferences.php">Preferences</a>
                </li>

                <!-- Theme Toggle -->
                <li class="nav-item">
                    <button class="btn btn-outline-light btn-sm ms-3" id="toggleThemeBtn">
                        Toggle Theme
                    </button>
                </li>

            </ul>
        </div>
    </div>
</nav>