<?php
require_once __DIR__ . '/db_connect.php';
header('Content-Type: application/json; charset=utf-8');

$query = trim($_GET['query'] ?? '');

if ($query === '') {
    echo json_encode([
        "success" => true,
        "keywords" => []
    ]);
    exit;
}

$sql = "
SELECT
  keyword_id AS id,
  name,
  question_count,
  participant_count,
  created_at
FROM keywords
WHERE name LIKE ?
ORDER BY question_count DESC, created_at DESC
LIMIT 20
";


$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode([
        "success" => false,
        "error" => "SQL prepare failed"
    ]);
    $conn->close();
    exit;
}

$like = "%{$query}%";
$stmt->bind_param("s", $like);
$stmt->execute();

$result = $stmt->get_result();
$keywords = [];

while ($row = $result->fetch_assoc()) {
    $keywords[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode([
    "success" => true,
    "keywords" => $keywords
]);
