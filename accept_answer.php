<?php
require_once __DIR__ . "/db_connect.php";

$answer_id = $_POST['answer_id'] ?? 0;
$question_id = $_POST['question_id'] ?? 0;

if (!$answer_id || !$question_id) {
    echo json_encode(["status" => "error"]);
    exit;
}

$conn->query("UPDATE answers SET is_accepted = 0 WHERE question_id = $question_id");

$stmt = $conn->prepare(
    "UPDATE answers SET is_accepted = 1 WHERE answer_id = ?"
);
$stmt->bind_param("i", $answer_id);
$stmt->execute();

echo json_encode(["status" => "success"]);
$conn->close();
