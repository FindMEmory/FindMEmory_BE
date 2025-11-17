<?php
require_once __DIR__ . '/db_connect.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $_POST["name"] = $_GET["name"] ?? '';
}

$name = $_POST['name'] ?? '';

if (!$name) {
    echo json_encode(["success" => false, "error" => "name은 필수입니다"]);
    exit;
}

$sql = "INSERT INTO keywords (name, created_at)
        VALUES ('$name', NOW())";

if ($conn->query($sql)) {
    echo json_encode([
        "success" => true,
        "message" => "키워드 등록 성공",
        "keyword_id" => $conn->insert_id
    ]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>
