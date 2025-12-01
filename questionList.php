<?php
require_once __DIR__ . '/db_connect.php';

header('Content-Type: application/json; charset=utf-8');

$isSolved = isset($_GET['isSolved']) ? $_GET['isSolved'] : 'all';

$where = "WHERE 1 = 1";

if ($isSolved === 'true') {
    $where .= " AND is_solved = 1";
} else if ($isSolved === 'false') {
    $where .= " AND is_solved = 0";
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'like';

switch ($sort) {
    //최신순
    case 'date':
        $orderBy = "ORDER BY created_at DESC";
        break;
    //답변 기다리고 있는 (채택이 없는 게시글)
    case 'not_solved':
        $where .= " AND is_solved = 0";
        $orderBy = "ORDER BY created_at DESC";

        break;
    //인기순
    case 'like':
    default:
        $orderBy = "ORDER BY like_count DESC";
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
    'data' => $rows
], JSON_UNESCAPED_UNICODE);

mysqli_close($conn);
?>