<?php
$conn = new mysqli("localhost", "root", "비밀번호", "findmemory");

$user_id = $_POST["user_id"] ?? "";

if ($user_id == "") {
    echo json_encode(["success" => false]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

echo json_encode(["success" => true]);
