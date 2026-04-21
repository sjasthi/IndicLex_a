<?php
// IndicLex REST API - Search Endpoint
header('Content-Type: application/json');
include_once '../includes/db_mysqli.php';
include_once '../includes/functions.php';

$query = isset($_GET['q']) ? sanitize($_GET['q']) : '';
$dict_id = isset($_GET['dict']) ? (int)$_GET['dict'] : 0;
$mode = isset($_GET['mode']) ? sanitize($_GET['mode']) : 'exact';

if (empty($query)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Query parameter 'q' is required."
    ]);
    exit;
}

$sql = "SELECT entry_id FROM dictionary_entries WHERE 1=1 ";
$params = [];


if ($dict_id >= 0) {
    $sql .= "AND dict_id='$dict_id'";

}

switch ($mode) {
    case 'prefix':
        // WORKING
        $sql .= " AND (lang_1 LIKE '$query%' OR lang_2 LIKE '$query%' OR lang_3 LIKE '$query%')";
        break;
    case 'suffix':
        // WORKING
        $sql .= " AND (lang_1 LIKE '%$query' OR lang_2 LIKE '%$query' OR lang_3 LIKE '%$query')";
        break;
    case 'substring':
        // WORKING
        $sql .= " AND (lang_1 LIKE '%$query%' OR lang_2 LIKE '%$query%' OR lang_3 LIKE '%$query%')";
        break;
    case 'exact': // WORKING
    default:
        // WORKING
        $sql .= " AND lang_1 = '$query' OR lang_2 LIKE '$query' OR lang_3 LIKE '$query'";
        break;
}

// Echo for DEBUGGING
//echo $sql;
try {
    //$stmt = $conn->prepare($sql);
    //$stmt->execute($params);
    //$stmt->store_result();
    //$results;
    //$stmt->bind_result($results);
    $results = $conn->query($sql);
    if (mysqli_num_rows($results) > 0) {
        $row = mysqli_fetch_assoc($results);
        echo json_encode([
            "status" => "success",
            "query" => $query,
            "mode" => $mode,
            "count" => mysqli_num_rows($results),
            "results" => $row
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "No results found for '$query' in '$mode' mode."
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Internal server error."
    ]);
}
?>
