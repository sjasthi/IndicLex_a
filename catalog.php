<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/db_mysqli.php';

$sql = "SELECT dict_id, name FROM dictionaries ORDER BY name ASC";
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

<?php include 'includes/navbar.php'; ?>

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
              <p class="text-muted mb-0">ID: <?php echo (int)$d['dict_id']; ?></p>
            </div>
            <div class="card-footer bg-transparent">
              <a class="btn btn-outline-primary btn-sm"
                 href="search.php?dict=<?php echo (int)$d['dict_id']; ?>">
                Search this dictionary
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>