<?php
require_once __DIR__ . '/db_connect.php';

$keyword_id = $_REQUEST['keyword_id'] ?? null;
$user_id    = $_REQUEST['sender_id'] ?? null;
$body       = $_REQUEST['body'] ?? null;

if (!$keyword_id || !$user_id || !$body) {
    echo 0;
    exit;
}


$conn->query("
    INSERT INTO keyword_chats (keyword_id, sender_id, body)
    VALUES ($keyword_id, $user_id, '$body')
");


$check = $conn->query("
    SELECT 1 FROM keyword_participants
    WHERE keyword_id = $keyword_id AND user_id = $user_id
    LIMIT 1
");


if ($check->num_rows === 0) {
    $conn->query("
        INSERT INTO keyword_participants (keyword_id, user_id)
        VALUES ($keyword_id, $user_id)
    ");

    $conn->query("
        UPDATE keywords
        SET participant_count = participant_count + 1
        WHERE keyword_id = $keyword_id
    ");
}

$conn->close();
echo 1;
