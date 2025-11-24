<?php
require_once __DIR__ . '/db_connect.php';

$title = $_POST['title'] ?? '';
$body = $_POST['body'] ?? '';
$author_id = $_POST['author_id'] ?? 0;
$keyword_id = $_POST['keyword_id'] ?? null;

if (empty($title) || empty($body) || empty($author_id)) {
    echo json_encode(["status" => "error", "message" => "필수 입력 누락"]);
    exit;
}

$sql = "INSERT INTO questions (title, body, author_id, keyword_id)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssis", $title, $body, $author_id, $keyword_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "질문 등록 성공"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

mysqli_close($conn);
?>
