<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/db_mysqli.php';

$sql = "SELECT dict_id, dict_identifier, name, description FROM dictionaries ORDER BY name ASC";
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

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<body class="<?php echo $bodyClass ?>">
<main>
    <section class="hero-section text-center d-flex align-items-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to IndicLex</h1>
            <p class="lead mt-3">
                A powerful multilingual dictionary management & search platform.
            </p>
            <a href="#dict-catalog" class="btn btn-primary btn-lg mt-4">
                Explore Dictionaries
            </a>
        </div>
    </section>
</main>

<main class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 id="dict-catalog" class="h3 mb-0">Dictionary Catalog</h1>
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
              <p class="text-muted mb-0">Identifier: <?php echo $d['dict_identifier']; ?></p>
              <br>
              <h3 class="h6 card-title mb-1">Description:</h3>
              <p class="mb-0"><?php echo $d['description']; ?></p>
            </div>
            <div class="card-footer bg-transparent">
              <a class="btn btn-outline-primary btn-sm"
                 href="search.php?dict=<?php echo (int)$d['dict_id']; ?>">
                Search this dictionary
              </a>
              <div class="row">
                <form method="POST" action="export.php?dict=<?php echo (int)$d['dict_id'];?>&name=<?php echo $d['name']?>">
                  <select class="" name="export-format">
                    <option value="xslx">.xslx</option>
                    <option value="csv">.csv</option>
                    <option value="html">.html</option>
                    <!-- <option value="50" <?php // echo ((int)$currentResultsPerPage === 50) ? 'selected' : ''; ?>>50</option> -->
                  </select>
                  <input type="submit" name="export-submit" value="Export" class="btn btn-outline-primary btn-sm">
                </form>
              </div>
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