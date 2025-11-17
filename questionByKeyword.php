<?php
require_once __DIR__ . '/db_connect.php';
header("Content-Type: application/json; charset=utf-8");

$keyword_id = $_GET['keyword_id'] ?? '';

if (!$keyword_id) {
    echo json_encode([
        "success" => false,
        "error" => "keyword_id is required"
    ]);
    exit;
}

$sql = "
    SELECT question_id, author_id, body, keyword_id,
           answer_count, title, like_count, view_count,
           is_solved, created_at, updated_at
    FROM questions
    WHERE keyword_id = ?
    ORDER BY question_id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $keyword_id);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];

while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

echo json_encode([
    "success" => true,
    "sort" => "keyword",
    "data" => $questions
], JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
?>
