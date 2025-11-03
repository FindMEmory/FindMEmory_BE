<?php
require_once __DIR__ . '/db_connect.php';

$login_id  = $_POST['login_id'];
$login_pwd = $_POST['login_pwd'];
$nickname  = $_POST['nickname'];

$sql = "INSERT INTO users (login_id, login_pwd, nickname, grade, status, created_at)
        VALUES ('$login_id', '$login_pwd', '$nickname', 1, 1, NOW())";

if ($conn->query($sql) === TRUE) {
    echo "가입 성공";
} else {
    echo "가입 실패: " . $conn->error;
}

$conn->close();
?>
