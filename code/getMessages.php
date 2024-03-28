<?php
$db = new SQLite3('messages.db');
// This line ensures that any messages older than 5 minutes are deleted from the database on every call to getMessages.
$db->exec("DELETE FROM messages WHERE timestamp < datetime('now', '-5 minute')");

$results = $db->query('SELECT * FROM messages ORDER BY timestamp ASC LIMIT 50');  // Fetches the last 50 messages

$messages = [];
while($row = $results->fetchArray(SQLITE3_ASSOC)) {
    $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode(['messages' => $messages]);