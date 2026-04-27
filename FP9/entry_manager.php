<?php
// FP5/admin/dashboard.php
require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/includes/db_mysqli.php';

if (!isset($_GET['dict'])) {
    header("Location: index.php");
    exit;
}

$defaultSearch = '';
if (isset($_GET['entry'])) {
    $defaultSearch = htmlspecialchars($_GET['entry']);
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

// ADD IF POSTED
if (isset($_POST['add'])) {
    // SQL to add a record
    $addLang1 = $_POST['addlang1'];
    $addLang2 = $_POST['addlang2'];
    $addLang3 = $_POST['addlang3'];
    $sql = "INSERT INTO dictionary_entries (dict_id, lang_1, lang_2, lang_3) VALUES ('$dictID', '$addLang1', '$addLang2', '$addLang3')";

    if ($conn->query($sql) === TRUE) {
        //echo "Record updated successfully";
    } else {
        //echo "Error updating record: " . $conn->error;
    }
}

// UPDATE IF POSTED
if (isset($_POST['update']) && isset($_POST['entryID'])) {
    // SQL to update a record
    $updateEntryID = $_POST['entryID'];
    $lang1 = $_POST['lang1'];
    $lang2 = $_POST['lang2'];
    $lang3 = $_POST['lang3'];
    $sql = "UPDATE dictionary_entries SET lang_1='$lang1', lang_2='$lang2', lang_3='$lang3' WHERE entry_id='$updateEntryID'";

    if ($conn->query($sql) === TRUE) {
        //echo "Record updated successfully";
    } else {
        //echo "Error updating record: " . $conn->error;
    }
}

// DELETE IF POSTED
if (isset($_POST['delete']) && isset($_POST['entryID'])) {
    // SQL to delete a record
    $deleteEntryID = $_POST['entryID'];
    $sql = "DELETE FROM dictionary_entries WHERE entry_id='$deleteEntryID'";

    if ($conn->query($sql) === TRUE) {
        //echo "Record deleted successfully";
    } else {
        //echo "Error deleting record: " . $conn->error;
    }
}

// Get dictionary details
$stmt = $conn->prepare("SELECT name, description FROM dictionaries WHERE dict_id = ?");
$stmt->bind_param("s", $dictID);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($dictName, $dictDesc);
$stmt->fetch();

// Get entries
$entries = [];
$entrySql = "SELECT * FROM dictionary_entries WHERE dict_id='$dictID'";
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
    <div class="mb-4">
        <h1 class="h1 mb-1">Entry Manager</h1><br>
        <h3 class="h3 mb-0"><?php echo $dictName; ?></h4>
        <p class="mb-0"><?php echo $dictDesc; ?></p>
    </div>

    <div class="card">
    <div class="mb-4 card-body">
        <h4 class="h4 mb-1">Add Entry</h4>
        <table>
            <thead>
                <tr>
                    <th>Language 1</th>
                    <th>Language 2</th>
                    <th>Language 3</th>
                </tr>
            </thead>
            <tbody>
                <form method="POST">
                    <td><input class="w-100" type="text" name="addlang1" value="" placeholder="Language One" required></td>
                    <td><input class="w-100" type="text" name="addlang2" value="" placeholder="Language Two" required></td>
                    <td><input class="w-100" type="text" name="addlang3" value="" placeholder="Language Three"></td>
                    <td>
                        <button type="submit" name="add" class="btn btn-outline-primary btn-sm">Add</button>
                    </td>
                </form>
            </tbody>
        </table>
    </div>
    </div>
    <br>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h2 class="h2 mb-3">Manage Entries</h2>
            <div class="table-responsive">
                <table id="dictionaryTable" class="table table-striped table-bordered align-middle w-100">
                    <thead>
                        <tr>
                            <th>Entry ID</th>
                            <th>Language 1</th>
                            <th>Language 2</th>
                            <th>Language 3</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entries as $row): ?>
                            <tr>
                                <form method="POST">
                                <td><?php echo (int)$row['entry_id']; ?><input type="hidden" name="entryID" value="<?php echo htmlspecialchars($row['entry_id']); ?>"></td>
                                <td><input class="w-100" type="text" name="lang1" value="<?php echo htmlspecialchars($row['lang_1']); ?>"></td>
                                <td><input class="w-100" type="text" name="lang2" value="<?php echo htmlspecialchars($row['lang_2']); ?>"></td>
                                <td><input class="w-100" type="text" name="lang3" value="<?php echo htmlspecialchars($row['lang_3']); ?>"></td>
                                <td>
                                    <button type="submit" name="update" class="btn btn-outline-primary btn-sm">Update</button>
                                    <button type="submit" name="delete" class="btn btn-outline-danger btn-sm">Delete</button>
                                </td>
                                </form>
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
        info: true,
        search: {
            search: '<?php echo $defaultSearch ?>'
        }
    });
});
</script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
<?php $conn->close(); ?>