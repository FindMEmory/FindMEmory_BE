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

/*
 * ✅ 핵심 규칙
 * 1. keyword_id가 POST에 없으면 → 키워드 유지
 * 2. keyword_id가 '' 이면 → 키워드 제거
 * 3. keyword_id가 숫자면 → 키워드 변경
 */

if (array_key_exists('keyword_id', $_POST)) {

    // 🔴 키워드 제거
    if ($_POST['keyword_id'] === '') {
        $sql = "
            UPDATE questions
            SET title = ?, body = ?, keyword_id = NULL, updated_at = NOW()
            WHERE question_id = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $body, $question_id);

    } else {
        // 🟢 키워드 변경
        $keyword_id = (int)$_POST['keyword_id'];

        $sql = "
            UPDATE questions
            SET title = ?, body = ?, keyword_id = ?, updated_at = NOW()
            WHERE question_id = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $title, $body, $keyword_id, $question_id);
    }

} else {
    // 🟡 키워드 유지 (POST에 아예 안 온 경우)
    $sql = "
        UPDATE questions
        SET title = ?, body = ?, updated_at = NOW()
        WHERE question_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $body, $question_id);
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
