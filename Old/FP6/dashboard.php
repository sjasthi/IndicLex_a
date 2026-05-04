<?php
// FP5/admin/dashboard.php
require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/includes/db_mysqli.php';

/* Top statistics */
$totalDictionaries = 0;
$totalWords = 0;
$totalLanguages = 0;

$q1 = $conn->query("SELECT COUNT(*) AS total FROM dictionaries");
if ($q1) {
    $totalDictionaries = (int)$q1->fetch_assoc()['total'];
}

$q2 = $conn->query("SELECT COUNT(*) AS total FROM dictionary_entries");
if ($q2) {
    $totalWords = (int)$q2->fetch_assoc()['total'];
}

$q3 = $conn->query("
    SELECT COUNT(DISTINCT language_name) AS total FROM (
        SELECT source_lang_1 AS language_name FROM dictionaries WHERE source_lang_1 IS NOT NULL AND source_lang_1 <> ''
        UNION
        SELECT source_lang_2 FROM dictionaries WHERE source_lang_2 IS NOT NULL AND source_lang_2 <> ''
        UNION
        SELECT source_lang_3 FROM dictionaries WHERE source_lang_3 IS NOT NULL AND source_lang_3 <> ''
    ) AS langs
");
if ($q3) {
    $totalLanguages = (int)$q3->fetch_assoc()['total'];
}

/* Per-dictionary word counts */
$dictStats = [];
$dictSql = "
    SELECT 
        d.dict_id,
        d.name,
        d.dict_identifier,
        d.type,
        COUNT(e.dict_id) AS word_count
    FROM dictionaries d
    LEFT JOIN dictionary_entries e ON e.dict_id = d.dict_id
    GROUP BY d.dict_id, d.name, d.dict_identifier, d.type
    ORDER BY d.name ASC
";
$dictResult = $conn->query($dictSql);
if ($dictResult) {
    while ($row = $dictResult->fetch_assoc()) {
        $dictStats[] = $row;
    }
}

/* Language-wise breakdown */
$languageStats = [];
$langSql = "
    SELECT language_name, COUNT(*) AS dictionary_count
    FROM (
        SELECT source_lang_1 AS language_name FROM dictionaries WHERE source_lang_1 IS NOT NULL AND source_lang_1 <> ''
        UNION ALL
        SELECT source_lang_2 FROM dictionaries WHERE source_lang_2 IS NOT NULL AND source_lang_2 <> ''
        UNION ALL
        SELECT source_lang_3 FROM dictionaries WHERE source_lang_3 IS NOT NULL AND source_lang_3 <> ''
    ) AS language_union
    GROUP BY language_name
    ORDER BY dictionary_count DESC, language_name ASC
";
$langResult = $conn->query($langSql);
if ($langResult) {
    while ($row = $langResult->fetch_assoc()) {
        $languageStats[] = $row;
    }
}
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<body>

<main class="container py-4">
    <div class="mb-4">
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Total Dictionaries</div>
                    <div class="display-6 fw-bold"><?php echo $totalDictionaries; ?></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Total Words</div>
                    <div class="display-6 fw-bold"><?php echo $totalWords; ?></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Languages Used</div>
                    <div class="display-6 fw-bold"><?php echo $totalLanguages; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h2 class="h5 mb-3">Dictionary Word Counts</h2>
            <div class="table-responsive">
                <table id="dictionaryTable" class="table table-striped table-bordered align-middle w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Dictionary Name</th>
                            <th>Identifier</th>
                            <th>Type</th>
                            <th>Word Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dictStats as $row): ?>
                            <tr>
                                <td><?php echo (int)$row['dict_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['dict_identifier']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo (int)$row['word_count']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h2 class="h5 mb-3">Language-wise Breakdown</h2>
            <div class="table-responsive">
                <table id="languageTable" class="table table-striped table-bordered align-middle w-100">
                    <thead>
                        <tr>
                            <th>Language</th>
                            <th>Dictionary Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($languageStats as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['language_name']); ?></td>
                                <td><?php echo (int)$row['dictionary_count']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.3.7/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    $('#dictionaryTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true
    });

    $('#languageTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true
    });
});
</script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
<?php $conn->close(); ?>