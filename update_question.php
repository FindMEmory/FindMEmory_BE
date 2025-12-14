<?php
require_once __DIR__ . '/db_connect.php';

$question_id = $_POST['question_id'] ?? '';
$title       = $_POST['title'] ?? '';
$body        = $_POST['body'] ?? '';
$keyword_id  = $_POST['keyword_id'] ?? null;

// 필수값 체크
if ($question_id === '' || $title === '' || $body === '') {
    echo json_encode([
        "success" => false,
        "msg" => "필수값 누락"
    ]);
    exit;
}

/*
 * ✅ 수정에서는 question_count 절대 건드리지 않는다
 * (개수는 추가 / 삭제에서만 변경)
 */

if ($keyword_id === null || $keyword_id === '') {
    // 키워드 제거
    $sql = "
        UPDATE questions
        SET title = ?, body = ?, keyword_id = NULL, updated_at = NOW()
        WHERE question_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $body, $question_id);
} else {
    // 키워드 변경/유지
    $sql = "
        UPDATE questions
        SET title = ?, body = ?, keyword_id = ?, updated_at = NOW()
        WHERE question_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $body, $keyword_id, $question_id);
}

$result = $stmt->execute();

if ($result) {
    echo json_encode([
        "success" => true,
        "msg" => "수정 완료"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "msg" => $conn->error
    ]);
}

$stmt->close();
$conn->close();
