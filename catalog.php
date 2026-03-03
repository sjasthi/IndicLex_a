<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . 'db_mysqli.php';

$sql = "SELECT id, name FROM dictionary ORDER BY name ASC";
$result = $conn->query($sql);

$dictionaries = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $dictionaries[] = $row;
  }
} else {
  die("Query failed: " . $conn->error);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dictionary Catalog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">IndicLex</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="catalog.php">Catalog</a></li>
        <li class="nav-item"><a class="nav-link" href="search.php">Search</a></li>
        <li class="nav-item"><a class="nav-link" href="preferences.php">Preferences</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Dictionary Catalog</h1>
    <a class="btn btn-primary btn-sm" href="search.php">Go to Search</a>
  </div>

  <?php if (empty($dictionaries)): ?>
    <div class="alert alert-info">No dictionaries yet.</div>
  <?php else: ?>
    <div class="row g-3">
      <?php foreach ($dictionaries as $d): ?>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h2 class="h5 card-title mb-1">
                <?php echo htmlspecialchars($d['name']); ?>
              </h2>
              <p class="text-muted mb-0">ID: <?php echo (int)$d['id']; ?></p>
            </div>
            <div class="card-footer bg-transparent">
              <a class="btn btn-outline-primary btn-sm"
                 href="search.php?dict=<?php echo (int)$d['id']; ?>">
                Search this dictionary
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<footer class="border-top py-3">
  <div class="container small text-muted">
    © <?php echo date('Y'); ?> IndicLex
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
