<?php
require_once __DIR__ . '/db_connect.php';

// GET 또는 POST 둘 중 하나에서 값 받아오기
$keyword_id = $_REQUEST['keyword_id'] ?? null;
$sender_id  = $_REQUEST['sender_id'] ?? null;
$body       = $_REQUEST['body'] ?? null;

// 값이 없으면 오류 처리
if ($keyword_id === null || $sender_id === null || $body === null) {
    echo "PARAM_ERROR";
    exit;
}

$sql = "INSERT INTO keyword_chats (keyword_id, sender_id, body)
        VALUES ('$keyword_id', '$sender_id', '$body')";

$result = mysqli_query($conn, $sql);

echo $result ? 1 : 0;
?>
