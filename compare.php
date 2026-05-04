<?php session_start(); ?>
<?php
require_once __DIR__ . '/includes/db_mysqli.php';

$dictionaries = [];
$dictSql = "SELECT dict_id, name FROM dictionaries WHERE is_active = 1 ORDER BY name ASC";
$dictResult = $conn->query($dictSql);

if ($dictResult) {
    while ($row = $dictResult->fetch_assoc()) {
        $dictionaries[] = $row;
    }
}

$dictA = $_GET['dictA'] ?? '';
$dictB = $_GET['dictB'] ?? '';

$sharedEntries = [];
$uniqueA = [];
$uniqueB = [];
$matchingTranslations = [];

function fetchRows($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    return $rows;
}

if (!empty($dictA) && !empty($dictB) && $dictA !== $dictB) {
    $dictA = (int)$dictA;
    $dictB = (int)$dictB;

    // Shared entries: same lang_1 appears in both dictionaries
    $stmt = $conn->prepare("
        SELECT 
            a.lang_1,
            a.lang_2 AS dict_a_translation,
            b.lang_2 AS dict_b_translation
        FROM dictionary_entries a
        JOIN dictionary_entries b
            ON a.lang_1 = b.lang_1
        WHERE a.dict_id = ?
          AND b.dict_id = ?
          AND a.is_active = 1
          AND b.is_active = 1
        ORDER BY a.lang_1 ASC
    ");
    $stmt->bind_param("ii", $dictA, $dictB);
    $sharedEntries = fetchRows($stmt);

    // Unique to Dictionary A
    $stmt = $conn->prepare("
        SELECT a.lang_1, a.lang_2
        FROM dictionary_entries a
        LEFT JOIN dictionary_entries b
            ON a.lang_1 = b.lang_1
           AND b.dict_id = ?
           AND b.is_active = 1
        WHERE a.dict_id = ?
          AND a.is_active = 1
          AND b.lang_1 IS NULL
        ORDER BY a.lang_1 ASC
    ");
    $stmt->bind_param("ii", $dictB, $dictA);
    $uniqueA = fetchRows($stmt);

    // Unique to Dictionary B
    $stmt = $conn->prepare("
        SELECT b.lang_1, b.lang_2
        FROM dictionary_entries b
        LEFT JOIN dictionary_entries a
            ON a.lang_1 = b.lang_1
           AND a.dict_id = ?
           AND a.is_active = 1
        WHERE b.dict_id = ?
          AND b.is_active = 1
          AND a.lang_1 IS NULL
        ORDER BY b.lang_1 ASC
    ");
    $stmt->bind_param("ii", $dictA, $dictB);
    $uniqueB = fetchRows($stmt);

    // Overlapping translations: same word and same translation
    $stmt = $conn->prepare("
        SELECT a.lang_1, a.lang_2
        FROM dictionary_entries a
        JOIN dictionary_entries b
            ON a.lang_1 = b.lang_1
           AND a.lang_2 = b.lang_2
        WHERE a.dict_id = ?
          AND b.dict_id = ?
          AND a.is_active = 1
          AND b.is_active = 1
        ORDER BY a.lang_1 ASC
    ");
    $stmt->bind_param("ii", $dictA, $dictB);
    $matchingTranslations = fetchRows($stmt);
}
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container my-5">
    <h1 class="mb-4">Dictionary Comparison</h1>

    <form method="GET" class="card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Dictionary A</label>
                <select name="dictA" class="form-select" required>
                    <option value="">Select dictionary</option>
                    <?php foreach ($dictionaries as $dict): ?>
                        <option value="<?php echo (int)$dict['dict_id']; ?>"
                            <?php echo ((string)$dictA === (string)$dict['dict_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dict['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-5">
                <label class="form-label">Dictionary B</label>
                <select name="dictB" class="form-select" required>
                    <option value="">Select dictionary</option>
                    <?php foreach ($dictionaries as $dict): ?>
                        <option value="<?php echo (int)$dict['dict_id']; ?>"
                            <?php echo ((string)$dictB === (string)$dict['dict_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dict['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Compare</button>
            </div>
        </div>
    </form>

    <?php if (!empty($dictA) && !empty($dictB) && $dictA === $dictB): ?>
        <div class="alert alert-warning">Please select two different dictionaries.</div>
    <?php endif; ?>

    <?php if (!empty($dictA) && !empty($dictB) && $dictA !== $dictB): ?>

        <h3>Shared Entries</h3>
        <table class="table table-bordered table-striped mb-5">
            <thead>
                <tr>
                    <th>Word</th>
                    <th>Dictionary A Translation</th>
                    <th>Dictionary B Translation</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sharedEntries)): ?>
                    <tr><td colspan="3">No shared entries found.</td></tr>
                <?php else: ?>
                    <?php foreach ($sharedEntries as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['lang_1']); ?></td>
                            <td><?php echo htmlspecialchars($row['dict_a_translation']); ?></td>
                            <td><?php echo htmlspecialchars($row['dict_b_translation']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <a href="validate.php?dict=<?php echo $dictA ?>" class="btn btn-sm btn-primary m-1">Validate Dictionary A</a>
                    <a href="validate.php?dict=<?php echo $dictB ?>" class="btn btn-sm btn-primary m-1">Validate Dictionary B</a>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-6">
                <h3>Unique to Dictionary A</h3>
                <table class="table table-bordered table-striped mb-5">
                    <thead>
                        <tr>
                            <th>Word</th>
                            <th>Translation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($uniqueA)): ?>
                            <tr><td colspan="2">No unique entries found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($uniqueA as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['lang_1']); ?></td>
                                    <td><?php echo htmlspecialchars($row['lang_2']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h3>Unique to Dictionary B</h3>
                <table class="table table-bordered table-striped mb-5">
                    <thead>
                        <tr>
                            <th>Word</th>
                            <th>Translation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($uniqueB)): ?>
                            <tr><td colspan="2">No unique entries found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($uniqueB as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['lang_1']); ?></td>
                                    <td><?php echo htmlspecialchars($row['lang_2']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h3>Overlapping Translations</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Word</th>
                    <th>Matching Translation</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($matchingTranslations)): ?>
                    <tr><td colspan="2">No overlapping translations found.</td></tr>
                <?php else: ?>
                    <?php foreach ($matchingTranslations as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['lang_1']); ?></td>
                            <td><?php echo htmlspecialchars($row['lang_2']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>