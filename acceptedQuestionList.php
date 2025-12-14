<?php
require_once __DIR__ . "/db_connect.php";

$user_id = $_GET['user_id'] ?? 0;

if (!$user_id) {
    echo json_encode([
        "status" => "error",
        "message" => "user_id 없음"
    ]);
    exit;
}

/*
 * 내가 쓴 답변이 채택된 질문 목록
 */
$sql = "
    SELECT DISTINCT q.*
    FROM questions q
    JOIN answers a ON q.question_id = a.question_id
    WHERE a.author_id = ?
      AND a.is_accepted = 1
    ORDER BY q.updated_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $questions
]);

$stmt->close();
$conn->close();
