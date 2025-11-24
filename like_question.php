<?php
require_once __DIR__ . "/db_connect.php";

$question_id = $_POST['question_id'] ?? 0;
$user_id     = $_POST['user_id'] ?? 0;

if (!$question_id || !$user_id) {
    echo json_encode(["status" => "error", "message" => "필수 값 없음"]);
    exit;
}

// 이미 좋아요 눌렀는지 확인
$check = $conn->prepare("SELECT * FROM question_likes WHERE user_id = ? AND question_id = ?");
$check->bind_param("ii", $user_id, $question_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {

    // 좋아요 취소
    $del = $conn->prepare("DELETE FROM question_likes WHERE user_id = ? AND question_id = ?");
    $del->bind_param("ii", $user_id, $question_id);
    $del->execute();
    $conn->query("UPDATE questions SET like_count = like_count - 1 WHERE question_id = $question_id");

    echo json_encode(["status" => "unliked", "message" => "좋아요 취소"]);
} else {

    // 좋아요 추가
    $ins = $conn->prepare("INSERT INTO question_likes (user_id, question_id) VALUES (?, ?)");
    $ins->bind_param("ii", $user_id, $question_id);
    $ins->execute();
    $conn->query("UPDATE questions SET like_count = like_count + 1 WHERE question_id = $question_id");

    echo json_encode(["status" => "liked", "message" => "좋아요 성공"]);
}

$conn->close();
?>
