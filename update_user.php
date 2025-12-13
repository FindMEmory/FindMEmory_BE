<?php
require_once __DIR__ . '/db_connect.php';

$user_id  = $_POST["user_id"] ?? "";
$login_id = $_POST["login_id"] ?? "";
$nickname = $_POST["nickname"] ?? "";
$password = $_POST["password"] ?? "";

// 필수값 체크
if ($user_id === "" || $login_id === "" || $nickname === "") {
    echo json_encode([
        "success" => false,
        "msg" => "필수값 누락"
    ]);
    exit;
}

// 비밀번호까지 수정하는 경우
if ($password !== "") {
    $sql = "UPDATE users 
            SET login_id = ?, nickname = ?, login_pwd = ?
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $login_id, $nickname, $password, $user_id);
} 
// 비밀번호 수정 안 하는 경우
else {
    $sql = "UPDATE users 
            SET login_id = ?, nickname = ?
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $login_id, $nickname, $user_id);
}

$result = $stmt->execute();

if ($result) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode([
        "success" => false,
        "msg" => "수정 실패"
    ]);
}

$stmt->close();
$conn->close();
