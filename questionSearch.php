<?php
require_once __DIR__ . '/db_connect.php';

header('Content-Type: application/json; charset=utf-8');

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

$isSolved = isset($_GET['isSolved']) ? $_GET['isSolved'] : 'all';

// 기본 WHERE
$where = "WHERE 1 = 1";

if ($keyword !== '') {
    $safeKeyword = mysqli_real_escape_string($conn, $keyword);
    $where .= " AND title LIKE '%$safeKeyword%'";
}

if ($isSolved === 'true') {
    $where .= " AND is_solved = 1";
} else if ($isSolved === 'false') {
    $where .= " AND is_solved = 0";
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date';

switch ($sort) {
    case 'like':
        $orderBy = "ORDER BY like_count DESC";
        break;

    case 'date':
    default:
        $orderBy = "ORDER BY created_at DESC";
        break;
}

$sql = "SELECT * FROM questions $where $orderBy";

$result = mysqli_query($conn, $sql);

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = array_map(function($v) {
        return is_numeric($v) ? (int)$v : $v;
    }, $row);
}

echo json_encode([
    'success' => true,
    'sort' => $sort,
    'isSolved' => $isSolved,
    'keyword' => $keyword,
    'data' => $rows
], JSON_UNESCAPED_UNICODE);

mysqli_close($conn);
?>
