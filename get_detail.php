<?php
require_once __DIR__ . "/db_connect.php";

$question_id = $_GET['id'] ?? 0;

// 질문 ID 없으면 종료
if (!$question_id) {
    echo json_encode(["status" => "error", "message" => "id 없음"]);
    exit;
}

// 1) 질문 가져오기 — Jade DB 스키마에 맞게 수정됨
$q_sql = "
    SELECT 
        q.question_id,
        q.title,
        q.body,
        q.author_id,
        q.created_at,
        q.like_count,
        u.nickname
    FROM questions q
    JOIN users u ON q.author_id = u.user_id
    WHERE q.question_id = ?
";

$q_stmt = $conn->prepare($q_sql);
$q_stmt->bind_param("i", $question_id);
$q_stmt->execute();
$q_result = $q_stmt->get_result();
$q_row = $q_result->fetch_assoc();

if (!$q_row) {
    echo json_encode(["status" => "error", "message" => "질문 없음"]);
    exit;
}

// Swift에서 요구하는 key 형태에 맞춰 변환
$question = [
    "id" => (int)$q_row["question_id"],
    "title" => $q_row["title"],
    "body" => $q_row["body"],
    "nickname" => $q_row["nickname"],
    "created_at" => $q_row["created_at"],
    "author_id" => (int)$q_row["author_id"],
    "accepted_comment_id" => null,   // Jade DB 스키마에는 없음 → null
    "like_count" => (int)$q_row["like_count"]  // Jade DB는 like_count 사용
];


// 2) 댓글 가져오기 (정상)
$c_sql = "
    SELECT 
        a.answer_id AS id,
        a.body AS text,
        a.author_id,
        a.created_at,
        u.nickname
    FROM answers a
    JOIN users u ON a.author_id = u.user_id
    WHERE a.question_id = ?
    ORDER BY a.created_at ASC
";

$c_stmt = $conn->prepare($c_sql);
$c_stmt->bind_param("i", $question_id);
$c_stmt->execute();
$c_result = $c_stmt->get_result();

$comments = [];
while ($row = $c_result->fetch_assoc()) {
    $comments[] = [
        "id" => (int)$row["id"],
        "author_id" => (int)$row["author_id"],
        "nickname" => $row["nickname"],
        "text" => $row["text"],
        "created_at" => $row["created_at"]
    ];
}

echo json_encode([
    "status" => "success",
    "question" => $question,
    "comments" => $comments
], JSON_UNESCAPED_UNICODE);

$conn->close();
?>
