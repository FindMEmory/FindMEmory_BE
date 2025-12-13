<?php
require_once __DIR__ . '/db_connect.php';
header("Content-Type: application/json; charset=utf-8");

$sort = $_GET['sort'] ?? 'popular';

$orderBy = "participant_count DESC"; // 기본: 인기

if ($sort === 'recent') {
    $orderBy = "k.created_at DESC";
}

$sql = "
SELECT
    k.keyword_id AS id,
    k.name,
    k.question_count,
    COUNT(DISTINCT kc.sender_id) AS participant_count,
    k.created_at
FROM keywords k
LEFT JOIN keyword_chats kc
    ON kc.keyword_id = k.keyword_id
GROUP BY k.keyword_id
ORDER BY $orderBy
LIMIT 10
";

$result = $conn->query($sql);

$keywords = [];

while ($row = $result->fetch_assoc()) {
    $keywords[] = [
        "id" => (int)$row["id"],
        "name" => $row["name"],
        "question_count" => (int)$row["question_count"],
        "participant_count" => (int)$row["participant_count"],
        "created_at" => $row["created_at"]
    ];
}

echo json_encode([
    "success" => true,
    "keywords" => $keywords
], JSON_UNESCAPED_UNICODE);

$conn->close();
