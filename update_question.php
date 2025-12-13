<?php
require_once __DIR__ . '/db_connect.php';

$question_id = $_POST['question_id'] ?? '';
$title       = $_POST['title'] ?? '';
$body        = $_POST['body'] ?? '';

// 필수값 체크
if ($question_id === '' || $title === '' || $body === '') {
    echo json_encode([
        "success" => false,
        "msg" => "필수값 누락"
    ]);
    exit;
}

// 질문 수정
$sql = "
    UPDATE questions 
    SET title = ?, body = ?, updated_at = NOW()
    WHERE question_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $title, $body, $question_id);

$result = $stmt->execute();

if ($result) {
    echo json_encode([
        "success" => true,
        "msg" => "수정 완료"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "msg" => "수정 실패"
    ]);
}

$stmt->close();
$conn->close();
