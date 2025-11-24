<?php
require_once __DIR__ . "/db_connect.php";

$question_id = $_POST['question_id'] ?? 0;

if (!$question_id) {
    echo json_encode(["status" => "error", "message" => "question_id 없음"]);
    exit;
}

// 1) 먼저 답변 삭제 (외래키 대응)
$delAnswers = $conn->prepare("DELETE FROM answers WHERE question_id = ?");
$delAnswers->bind_param("i", $question_id);
$delAnswers->execute();

// 2) 질문 삭제
$delQ = $conn->prepare("DELETE FROM questions WHERE question_id = ?");
$delQ->bind_param("i", $question_id);
$delQ->execute();

if ($delQ->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "질문 삭제됨"]);
} else {
    echo json_encode(["status" => "error", "message" => "질문 삭제 실패"]);
}

$conn->close();
?>
