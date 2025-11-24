<?php
require_once __DIR__ . "/db_connect.php";

$answer_id = $_POST['answer_id'] ?? 0;

if (!$answer_id) {
    echo json_encode(["status" => "error", "message" => "answer_id 없음"]);
    exit;
}

// 실제 삭제
$stmt = $conn->prepare("DELETE FROM answers WHERE answer_id = ?");
$stmt->bind_param("i", $answer_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "댓글 삭제됨"]);
} else {
    echo json_encode(["status" => "error", "message" => "댓글 삭제 실패"]);
}

$conn->close();
?>
