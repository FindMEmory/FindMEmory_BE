<?php
require_once __DIR__ . '/db_connect.php';

header('Content-Type: application/json; charset=utf-8');

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'like';

switch ($sort) {
    //최신순
    case 'date':
        $orderBy = "ORDER BY created_at DESC";
        break;
    //답변 기다리고 있는 (채택이 없는 게시글)
    case 'notSolved':
        $orderBy = "where is_solved = 0;";
        break;
    //인기순
    case 'like':
    default:
        $orderBy = "ORDER BY like_count DESC";
        break;
}

$sql = "SELECT * FROM questions $orderBy";

$result = mysqli_query($conn, $sql);

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

echo json_encode([
    'success' => true,
    'sort' => $sort,
    'data' => $rows
], JSON_UNESCAPED_UNICODE);

mysqli_close($conn);
?>