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
    SELECT *
    FROM questions
    WHERE author_id = $user_id
    ORDER BY created_at DESC
";

$result = mysqli_query($conn, $sql);
$rows = [];

while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

echo json_encode([
    'success' => true,
    'user_id' => $user_id,
    'data' => $rows
], JSON_UNESCAPED_UNICODE);

mysqli_close($conn);
?>
