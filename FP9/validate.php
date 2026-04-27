<?php
// FP5/admin/dashboard.php
require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/includes/db_mysqli.php';

if (!isset($_GET['dict'])) {
    header("Location: index.php");
    exit;
}

// DELETE IF POSTED
if (isset($_POST['delete']) && isset($_POST['entryID'])) {
    // SQL to delete a record
    $deleteEntryID = $_POST['entryID'];
    $sql = "DELETE FROM dictionary_entries WHERE entry_id='$deleteEntryID'";

    if ($conn->query($sql) === TRUE) {
        // echo "Record deleted successfully";
    } else {
        // echo "Error deleting record: " . $conn->error;
    }
}


$dictCount = 0;
$dictID = htmlspecialchars($_GET['dict']);
$dictResult = $conn->query("SELECT COUNT(*) AS total FROM dictionaries WHERE dict_id='$dictID'");
if ($dictResult) {
    $dictCount = (int)$dictResult->fetch_assoc()['total'];
}
if ($dictCount < 1) {
    header("Location: index.php");
    exit;
}

// Get dictionary details
$stmt = $conn->prepare("SELECT name, description, type, source_lang_1, source_lang_2, source_lang_3 FROM dictionaries WHERE dict_id = ?");
$stmt->bind_param("s", $dictID);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($dictName, $dictDesc, $dictType, $dictLang1, $dictLang2, $dictLang3);
$stmt->fetch();

// Get entries
$entries = [];
$entrySql = "SELECT * FROM dictionary_entries WHERE dict_id='$dictID' ORDER BY entry_id";
$entryResult = $conn->query($entrySql);
if ($entryResult) {
    while ($row = $entryResult->fetch_assoc()) {
        $entries[] = $row;
    }
}

?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
<body class="">

<main class="container py-4">
    <h1>Dictionary Validator</h1>
    <div class="card shadow-sm border-0 mb-4">
        <div class="mb-4 card-body">
            <h2><?php echo $dictName; ?></h2>
            <h5>ID: <?php echo $dictID; ?></h5>
            <h5><?php echo $dictType; ?> (<?php echo $dictLang1." ".$dictLang2." ".$dictLang3 ?>)</h5>
            <p><?php echo $dictDesc; ?></p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <h1>Testing for duplicates in Dictionary <?php echo $dictID; ?></h1>
        <div class="card-body">
            <?php $duplicateCount = 0; ?>
            <?php foreach ($entries as $e): ?>
                <?php 
                $e1 = $e['lang_1'];
                $e2 = $e['lang_2'];
                $e3 = $e['lang_3'];
                $dupSql = "SELECT * FROM dictionary_entries WHERE dict_id != $dictID AND (
                    (lang_1 = '$e1' OR lang_2 = '$e1' OR lang_3 = '$e1')
                    AND (lang_1 = '$e2' OR lang_2 = '$e2' OR lang_3 = '$e2')
                    )";
                $dupResult = $conn->query($dupSql);
                ?>
                <?php if ($dupResult->num_rows > 0): ?>
                    <?php $duplicateCount += 1; ?>
                    <h3> <?php echo "Entry ".$e['entry_id'].", <strong>".$e['lang_1']." / ".$e['lang_2']." / ".$e['lang_3']."</strong> has duplicates:"; ?> </h3>
                    <table class="table table-striped table-bordered align-middle w-100">
                    <thead>
                        <tr>
                            <th>Dictionary ID</th>
                            <th>Entry ID</th>
                            <th>Language 1</th>
                            <th>Language 2</th>
                            <th>Language 3</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($dup = $dupResult->fetch_assoc()): ?>
                        <tr>
                            <form method="POST">
                            <input type="hidden" name="entryID" value="<?php echo htmlspecialchars($dup['entry_id']); ?>">
                            <td> <?php echo $dup['dict_id'] ?> </td>
                            <td> <?php echo $dup['entry_id'] ?> </td>
                            <td> <?php echo $dup['lang_1'] ?> </td>
                            <td> <?php echo $dup['lang_2'] ?> </td>
                            <td> <?php echo $dup['lang_3'] ?> </td>
                            <td>
                                <a href="entry_manager.php?dict=<?php echo $dup['dict_id'] ?>&entry=<?php echo $dup['entry_id'] ?>" class="btn btn-sm btn-warning">Manage Entry</a>
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete Duplicate</button>
                                <a href="validate.php?dict=<?php echo $dup['dict_id'] ?>" class="btn btn-sm btn-primary">Validate Dictionary</a>
                            </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                    <?php echo "<br>"; ?>
                    </tbody>
                    </table>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($duplicateCount < 1): ?>
                <h3>No duplicates found.</h3>
            <?php endif; ?>

        </div>
    </div>

</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.3.7/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

</script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
<?php $conn->close(); ?>