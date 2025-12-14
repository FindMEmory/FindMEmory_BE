<?php
require_once __DIR__ . "/db_connect.php";

$question_id = $_GET['id'] ?? 0;
if (!$question_id) {
    echo json_encode(["status" => "error", "message" => "id 없음"]);
    exit;
}

/* ===============================
   1️⃣ 질문 가져오기
================================ */
$q_sql = "
    SELECT 
        q.question_id,
        q.title,
        q.body,
        q.author_id,
        q.created_at,
        q.like_count,
        q.is_solved,
        u.nickname
    FROM questions q
    JOIN users u ON q.author_id = u.user_id
    LEFT JOIN keywords k ON q.keyword_id = k.keyword_id
    WHERE q.question_id = ?
";

$q_stmt = $conn->prepare($q_sql);
$q_stmt->bind_param("i", $question_id);
$q_stmt->execute();
$q_row = $q_stmt->get_result()->fetch_assoc();

if (!$q_row) {
    echo json_encode(["status" => "error", "message" => "질문 없음"]);
    exit;
}

/* ===============================
   2️⃣ 댓글 가져오기 + 채택 여부
================================ */
$c_sql = "
    SELECT 
        a.answer_id AS id,
        a.body AS text,
        a.author_id,
        a.created_at,
        a.is_accepted,
        u.nickname
    FROM answers a
    JOIN users u ON a.author_id = u.user_id
    WHERE a.question_id = ?
    ORDER BY a.is_accepted DESC, a.created_at ASC
";

$c_stmt = $conn->prepare($c_sql);
$c_stmt->bind_param("i", $question_id);
$c_stmt->execute();
$c_result = $c_stmt->get_result();

$comments = [];
$accepted_comment_id = null;

while ($row = $c_result->fetch_assoc()) {
    $isAccepted = (int)$row["is_accepted"] === 1;

    if ($isAccepted) {
        $accepted_comment_id = (int)$row["id"];
    }

    $comments[] = [
        "id" => (int)$row["id"],
        "author_id" => (int)$row["author_id"],
        "nickname" => $row["nickname"],
        "text" => $row["text"],
        "created_at" => $row["created_at"],
        "is_accepted" => $isAccepted
    ];
}

/* ===============================
   3️⃣ Swift 친화적 JSON
================================ */
echo json_encode([
    "status" => "success",
    "question" => [
        "id" => (int)$q_row["question_id"],
        "title" => $q_row["title"],
        "body" => $q_row["body"],
        "nickname" => $q_row["nickname"],
        "created_at" => $q_row["created_at"],
        "author_id" => (int)$q_row["author_id"],
        "like_count" => (int)$q_row["like_count"],
        "is_solved" => (bool)$q_row["is_solved"],
        "accepted_comment_id" => $accepted_comment_id
    ],
    "comments" => $comments
], JSON_UNESCAPED_UNICODE);

$conn->close();
