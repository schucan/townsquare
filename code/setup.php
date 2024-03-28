<?php
$db = new SQLite3('messages.db');

$result = $db->exec('CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_hash TEXT NOT NULL,
    name TEXT,
    message TEXT NOT NULL,
    timestamp TEXT NOT NULL
)');

if ($result) {
    echo "Table created or already exists.\n";
} else {
    echo "Failed to create table.\n";
}