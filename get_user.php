<?php
require_once __DIR__ . '/db_connect.php';

$user_id = $_GET['user_id'] ?? '';

if ($user_id == '') {
    echo json_encode(["success" => false]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT login_id, nickname FROM users WHERE user_id = ?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo json_encode([
        "success" => true,
        "login_id" => $user["login_id"],
        "nickname" => $user["nickname"]
    ]);
} else {
    echo json_encode(["success" => false]);
}

$conn->close();
