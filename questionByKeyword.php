<?php
require_once __DIR__ . '/db_connect.php';
header("Content-Type: application/json; charset=utf-8");

$keyword_id = $_GET['keyword_id'] ?? '';
$search = $_GET['search'] ?? '';

if (!$keyword_id) {
    echo json_encode([
        "success" => false,
        "error" => "keyword_id is required"
    ]);
    exit;
}

// 🔹 기본 조건
$where = "WHERE keyword_id = ?";
$params = [$keyword_id];
$types = "i";

// 🔹 검색어 조건 추가
if ($search !== '') {
    $where .= " AND (title LIKE ? OR body LIKE ?)";
    $searchLike = "%{$search}%";
    $params[] = $searchLike;
    $params[] = $searchLike;
    $types .= "ss";
}

$sql = "
    SELECT question_id, author_id, body, keyword_id,
           answer_count, title, like_count, view_count,
           is_solved, created_at, updated_at
    FROM questions
    $where
    ORDER BY question_id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();

$result = $stmt->get_result();
$questions = [];

while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

echo json_encode([
    "success" => true,
    "sort" => "keyword",
    "data" => $questions
], JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
?>
