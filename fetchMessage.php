<?php
require_once __DIR__ . '/db_connect.php';

header("Content-Type: application/json; charset=utf-8");

// GET 파라미터
$keyword_id = isset($_GET['keyword_id']) ? intval($_GET['keyword_id']) : 0;
$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

if ($keyword_id == 0) {
    echo json_encode([]);
    exit;
}

// 메시지 가져오기
$sql = "
    SELECT 
        c.chat_id,
        c.keyword_id,
        c.sender_id,
        c.body,
        c.created_at,
        u.nickname AS user_name
    FROM keyword_chats AS c
    LEFT JOIN users AS u
        ON c.sender_id = u.user_id
    WHERE c.keyword_id = $keyword_id
      AND c.chat_id > $last_id
    ORDER BY c.chat_id ASC
";

$result = mysqli_query($conn, $sql);

$list = [];

while ($row = mysqli_fetch_assoc($result)) {
    $list[] = [
        "chat_id"       => intval($row["chat_id"]),
        "keyword_id"    => intval($row["keyword_id"]),
        "sender_id"     => intval($row["sender_id"]),
        "body"          => $row["body"],
        "created_at"    => $row["created_at"],
        "user_name"     => $row["user_name"]
    ];
}

echo json_encode($list, JSON_UNESCAPED_UNICODE);
?>
