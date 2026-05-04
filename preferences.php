<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/includes/db_mysqli.php';

// Get system defaults from preferences table
$systemDefaultDict = 'all';
$systemResultsPerPage = 10;
$systemTheme = 'light';

$prefSql = "SELECT pref_key, pref_value FROM preferences";
$prefResult = $conn->query($prefSql);

if ($prefResult) {
    while ($row = $prefResult->fetch_assoc()) {
        if ($row['pref_key'] === 'default_dict') {
            $systemDefaultDict = $row['pref_value'];
        }
        if ($row['pref_key'] === 'results_per_page') {
            $systemResultsPerPage = (int)$row['pref_value'];
        }
        if ($row['pref_key'] === 'theme') {
            $systemTheme = $row['pref_value'];
        }
    }
}

// Save cookies on submit
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedDict = $_POST['default_dictionary'] ?? '';
    $selectedResults = $_POST['results_per_page'] ?? '10';
    $selectedTheme = $_POST['theme'] ?? 'light';

    setcookie('pref_dict', $selectedDict, time() + (86400 * 30), "/");
    setcookie('pref_results', $selectedResults, time() + (86400 * 30), "/");
    setcookie('pref_theme', $selectedTheme, time() + (86400 * 30), "/");

    $_COOKIE['pref_dict'] = $selectedDict;
    $_COOKIE['pref_results'] = $selectedResults;
    $_COOKIE['pref_theme'] = $selectedTheme;

    $successMessage = "Preferences saved successfully.";
}

// Cookies first, fallback on default settings
$currentDefaultDict = $_COOKIE['pref_dict'] ?? $systemDefaultDict;
$currentResultsPerPage = $_COOKIE['pref_results'] ?? $systemResultsPerPage;
$currentTheme = $_COOKIE['pref_theme'] ?? $systemTheme;

// Load the  dictionaries
$dictionaries = [];
$dictSql = "SELECT dict_id, name FROM dictionaries ORDER BY name ASC";
$dictResult = $conn->query($dictSql);

if ($dictResult) {
    while ($row = $dictResult->fetch_assoc()) {
        $dictionaries[] = $row;
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<body class="<?php echo $bodyClass ?>">
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <h1 class="text-center mb-4">Preferences</h1>

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="mb-3">
                    <label class="form-label">Default Dictionary</label>
                    <select class="form-select" name="default_dictionary">
                        <option value="">All Dictionaries</option>
                        <?php foreach ($dictionaries as $dict): ?>
                            <option value="<?php echo (int)$dict['dict_id']; ?>"
                                <?php echo ((string)$currentDefaultDict === (string)$dict['dict_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dict['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Results Per Page</label>
                    <select class="form-select" name="results_per_page">
                        <option value="5" <?php echo ((int)$currentResultsPerPage === 5) ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?php echo ((int)$currentResultsPerPage === 10) ? 'selected' : ''; ?>>10</option>
                        <option value="20" <?php echo ((int)$currentResultsPerPage === 20) ? 'selected' : ''; ?>>20</option>
                        <option value="50" <?php echo ((int)$currentResultsPerPage === 50) ? 'selected' : ''; ?>>50</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Theme</label>
                    <select class="form-select" name="theme">
                        <option value="light" <?php echo ($currentTheme === 'light') ? 'selected' : ''; ?>>Light</option>
                        <option value="dark" <?php echo ($currentTheme === 'dark') ? 'selected' : ''; ?>>Dark</option>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary me-2">
                        Save Changes
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php $conn->close(); ?>