<?php
require_once __DIR__ . '/db_connect.php';

header('Content-Type: application/json; charset=utf-8');

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'user_id is required'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "
    SELECT q.*
    FROM questions q
    JOIN answers a
    ON q.question_id = a.question_id
    WHERE a.author_id = $user_id
    AND a.is_accepted = 1
    ORDER BY q.created_at DESC
";

$result = mysqli_query($conn, $sql);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = array_map(function($v) {
        return is_numeric($v) ? (int)$v : $v;
    }, $row);
}

echo json_encode([
    'success' => true,
    'user_id' => $user_id,
    'data' => $rows
], JSON_UNESCAPED_UNICODE);

mysqli_close($conn);
?>
