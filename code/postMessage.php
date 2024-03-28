<?php
$db = new SQLite3('messages.db');

// clean up DB
$db->exec("DELETE FROM messages WHERE timestamp < datetime('now', '-5 minute')");

// Assuming the table has already been created by a setup script
$content = file_get_contents('php://input');
$decoded = json_decode($content, true);

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$userIP = $_SERVER['REMOTE_ADDR'];  // Note: Consider privacy implications
$userHash = hash('sha256', $userAgent . $userIP . $decoded['name']);  // Using the name as part of the hash

if(isset($decoded['message'])) {
    $stmt = $db->prepare('INSERT INTO messages (user_hash, name, message, timestamp) VALUES (:user_hash, :name, :message, datetime("now"))');
    $stmt->bindValue(':user_hash', $userHash, SQLITE3_TEXT);
    $stmt->bindValue(':name', $decoded['name'], SQLITE3_TEXT);
    $stmt->bindValue(':message', $decoded['message'], SQLITE3_TEXT);
    $stmt->execute();
}