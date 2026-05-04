<?php
// IndicLex REST API - Autocomplete Endpoint
header('Content-Type: application/json');
include_once '../includes/db.php';
include_once '../includes/functions.php';

$query = isset($_GET['q']) ? sanitize($_GET['q']) : '';
$dict_id = isset($_GET['dict']) ? (int)$_GET['dict'] : 0;

if (empty($query)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT DISTINCT word FROM dictionary_entries WHERE word LIKE ? ";
$params = [$query . '%'];

if ($dict_id > 0) {
    $sql .= " AND dictionary_id = ?";
    $params[] = $dict_id;
}

$sql .= " ORDER BY LENGTH(word) ASC LIMIT 10";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
