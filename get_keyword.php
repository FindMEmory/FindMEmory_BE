<?php
require_once __DIR__ . '/db_connect.php';
header("Content-Type: application/json; charset=utf-8");

$sql = "SELECT keyword_id AS id, name, created_at 
        FROM keywords 
        ORDER BY keyword_id DESC";
$result = $conn->query($sql);

$keywords = [];

while ($row = $result->fetch_assoc()) {
    $keywords[] = [
        "id" => (int)$row["id"],
        "name" => $row["name"],
        "created_at" => $row["created_at"]
    ];
}

echo json_encode([
    "success" => true,
    "keywords" => $keywords
], JSON_UNESCAPED_UNICODE);

$conn->close();
?>
