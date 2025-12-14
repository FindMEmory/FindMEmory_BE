<?php
require_once __DIR__ . "/db_connect.php";

$answer_id   = $_POST['answer_id'] ?? 0;
$question_id = $_POST['question_id'] ?? 0;

if (!$answer_id || !$question_id) {
    echo json_encode(["status" => "error", "message" => "필수 값 없음"]);
    exit;
}

/*
 * 0️⃣ 새로 채택될 답변의 작성자 가져오기
 */
$getAuthor = $conn->prepare("
    SELECT author_id 
    FROM answers 
    WHERE answer_id = ?
");
$getAuthor->bind_param("i", $answer_id);
$getAuthor->execute();
$getAuthor->bind_result($answerAuthorId);
$getAuthor->fetch();
$getAuthor->close();

/*
 * 1️⃣ 기존 채택된 답변 해제
 */
$conn->query("
    UPDATE answers 
    SET is_accepted = 0 
    WHERE question_id = $question_id
");

/*
 * 2️⃣ 선택한 답변 채택
 */
$accept = $conn->prepare("
    UPDATE answers 
    SET is_accepted = 1 
    WHERE answer_id = ?
");
$accept->bind_param("i", $answer_id);
$accept->execute();
$accept->close();

/*
 * 3️⃣ 질문 해결 처리
 */
$solve = $conn->prepare("
    UPDATE questions 
    SET is_solved = 1 
    WHERE question_id = ?
");
$solve->bind_param("i", $question_id);
$solve->execute();
$solve->close();

/*
 * 4️⃣ 답변 작성자 adopt_count +1 ⭐⭐⭐
 */
$updateUser = $conn->prepare("
    UPDATE users 
    SET adopt_count = adopt_count + 1 
    WHERE user_id = ?
");
$updateUser->bind_param("i", $answerAuthorId);
$updateUser->execute();
$updateUser->close();

echo json_encode([
    "status" => "success",
    "message" => "댓글 채택 완료"
]);

$conn->close();
