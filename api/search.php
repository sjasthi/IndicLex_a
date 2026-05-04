<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db_mysqli.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function json_response(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function bind_params(mysqli_stmt $stmt, string $types, array &$params): void
{
    $bind = [];
    $bind[] = $types;

    foreach ($params as $key => &$value) {
        $bind[] = &$value;
    }

    call_user_func_array([$stmt, 'bind_param'], $bind);
}

try {
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $dict = isset($_GET['dict']) ? trim((string)$_GET['dict']) : '0';
    $mode = isset($_GET['mode']) ? strtolower(trim((string)$_GET['mode'])) : 'exact';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

    if ($q === '') {
        json_response(400, [
            'status' => 'error',
            'message' => "Query parameter 'q' is required."
        ]);
    }

    if (!is_numeric($dict) || (int)$dict < 0) {
        json_response(400, [
            'status' => 'error',
            'message' => "Query parameter 'dict' must be a number 0 or greater."
        ]);
    }

    $allowedModes = ['exact', 'prefix', 'suffix', 'substring'];
    if (!in_array($mode, $allowedModes, true)) {
        json_response(400, [
            'status' => 'error',
            'message' => "Invalid mode. Allowed values are exact, prefix, suffix, substring."
        ]);
    }

    if ($limit < 1) {
        $limit = 20;
    }
    if ($limit > 50) {
        $limit = 50;
    }

    $dictId = (int)$dict;

    $where = [];
    $types = '';
    $params = [];

    if ($dictId > 0) {
        $where[] = 'e.dict_id = ?';
        $types .= 'i';
        $params[] = $dictId;
    }

    switch ($mode) {
        case 'prefix':
            $where[] = '(e.lang_1 LIKE ? OR e.lang_2 LIKE ? OR e.lang_3 LIKE ?)';
            $types .= 'sss';
            $params[] = $q . '%';
            $params[] = $q . '%';
            $params[] = $q . '%';
            break;

        case 'suffix':
            $where[] = '(e.lang_1 LIKE ? OR e.lang_2 LIKE ? OR e.lang_3 LIKE ?)';
            $types .= 'sss';
            $params[] = '%' . $q;
            $params[] = '%' . $q;
            $params[] = '%' . $q;
            break;

        case 'substring':
            $where[] = '(e.lang_1 LIKE ? OR e.lang_2 LIKE ? OR e.lang_3 LIKE ?)';
            $types .= 'sss';
            $params[] = '%' . $q . '%';
            $params[] = '%' . $q . '%';
            $params[] = '%' . $q . '%';
            break;

        case 'exact':
        default:
            $where[] = '(e.lang_1 = ? OR e.lang_2 = ? OR e.lang_3 = ?)';
            $types .= 'sss';
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
            break;
    }

    $sql = "
        SELECT
            e.entry_id,
            e.dict_id,
            d.name AS dict_name,
            d.dict_identifier,
            e.lang_1,
            e.lang_2,
            e.lang_3
        FROM dictionary_entries e
        LEFT JOIN dictionaries d ON d.dict_id = e.dict_id
    ";

    if (!empty($where)) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= ' ORDER BY e.lang_1 ASC, e.entry_id ASC LIMIT ?';
    $types .= 'i';
    $params[] = $limit;

    $stmt = $conn->prepare($sql);
    bind_params($stmt, $types, $params);
    $stmt->execute();

    $result = $stmt->get_result();
    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    if (count($rows) === 0) {
        json_response(404, [
            'status' => 'error',
            'message' => "No results found for '{$q}' in '{$mode}' mode."
        ]);
    }

    json_response(200, [
        'status' => 'success',
        'query' => $q,
        'dict' => $dictId,
        'mode' => $mode,
        'count' => count($rows),
        'results' => $rows
    ]);
} catch (Throwable $e) {
    json_response(500, [
        'status' => 'error',
        'message' => 'Internal server error.'
    ]);
}