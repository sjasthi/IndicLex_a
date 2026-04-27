<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("includes/db_mysqli.php");

if (!isset($conn)) {
    die("Database connection not found");
}

/* =========================
   TOTAL DICTIONARIES
========================= */
$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM dictionaries");
$totalDictionaries = mysqli_fetch_assoc($res)['total'] ?? 0;

/* =========================
   TOTAL WORDS
========================= */
$res = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM dictionary_entries 
    WHERE is_active = 1
");
$totalWords = mysqli_fetch_assoc($res)['total'] ?? 0;

/* =========================
   TYPE BREAKDOWN (PIE CHART)
========================= */
$res = mysqli_query($conn, "
    SELECT type, COUNT(*) AS total
    FROM dictionaries
    GROUP BY type
");

$types = [];
$typeCounts = [];

while ($row = mysqli_fetch_assoc($res)) {
    $types[] = $row['type'];
    $typeCounts[] = (int)$row['total'];
}

/* =========================
   LANGUAGE BREAKDOWN (BAR CHART)
========================= */
$res = mysqli_query($conn, "
    SELECT lang_1 AS language, COUNT(*) AS total
    FROM dictionary_entries
    WHERE lang_1 IS NOT NULL AND lang_1 != ''
    GROUP BY lang_1
");

$languages = [];
$langCounts = [];

while ($row = mysqli_fetch_assoc($res)) {
    $languages[] = $row['language'];
    $langCounts[] = (int)$row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>IndicLex Reports</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: Arial;
            background: #1f2328;
            color: white;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .box {
            flex: 1;
            background: #2b3137;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .card {
            background: #2b3137;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<h1>📊 IndicLex Reports </h1>

<!-- SUMMARY -->
<div class="stats">
    <div class="box">
        <h2><?= $totalDictionaries ?></h2>
        <p>Total Dictionaries</p>
    </div>

    <div class="box">
        <h2><?= $totalWords ?></h2>
        <p>Total Words</p>
    </div>

    <div class="box">
        <h2><?= count($languages) ?></h2>
        <p>Languages</p>
    </div>
</div>

<!-- CHARTS -->
<div class="grid">

    <!-- PIE CHART -->
    <div class="card">
        <h3>Dictionary Types</h3>
        <canvas id="typeChart"></canvas>
    </div>

    <!-- BAR CHART -->
    <div class="card">
        <h3>Language Distribution</h3>
        <canvas id="langChart"></canvas>
    </div>

</div>

<script>
/* =========================
   PIE CHART - TYPES
========================= */
new Chart(document.getElementById("typeChart"), {
    type: "pie",
    data: {
        labels: <?= json_encode($types) ?>,
        datasets: [{
            data: <?= json_encode($typeCounts) ?>,
            backgroundColor: [
                "#6366f1",
                "#22c55e",
                "#f59e0b",
                "#ef4444",
                "#06b6d4"
            ]
        }]
    }
});

/* =========================
   BAR CHART - LANGUAGES
========================= */
new Chart(document.getElementById("langChart"), {
    type: "bar",
    data: {
        labels: <?= json_encode($languages) ?>,
        datasets: [{
            label: "Dictionary Entries",
            data: <?= json_encode($langCounts) ?>,
            backgroundColor: "#4f46e5"
        }]
    },
    options: {
        responsive: true
    }
});
</script>

</body>
</html>