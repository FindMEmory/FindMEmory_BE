<?php
require_once __DIR__ . "/db_connect.php";

$question_id = $_POST['question_id'] ?? 0;
$author_id   = $_POST['author_id'] ?? 0;
$body        = $_POST['body'] ?? '';

if (!$question_id || !$author_id || empty($body)) {
    echo json_encode(["status" => "error", "message" => "필수 값 없음"]);
    exit;
}

$sql = "INSERT INTO answers (question_id, author_id, body, is_accepted)
        VALUES (?, ?, ?, b'0')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $question_id, $author_id, $body);

if ($stmt->execute()) {

    // 질문 테이블의 answer_count 증가
    $update = $conn->prepare("UPDATE questions SET answer_count = answer_count + 1 WHERE question_id = ?");
    $update->bind_param("i", $question_id);
    $update->execute();

    echo json_encode(["status" => "success", "message" => "댓글 등록 성공"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$conn->close();
?>
