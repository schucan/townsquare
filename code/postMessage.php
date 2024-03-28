<?php
$db = new SQLite3('messages.db');
$db->busyTimeout(5000); // Set a busy timeout to automatically retry for 5 seconds

$content = file_get_contents('php://input');
$decoded = json_decode($content, true);

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$userIP = $_SERVER['REMOTE_ADDR'];
$userHash = hash('sha256', $userAgent . $userIP); 

$name = filter_var($decoded['name'], FILTER_SANITIZE_STRING);
$message = filter_var($decoded['message'], FILTER_SANITIZE_STRING);

$maxTries = 5;

for ($try = 0; $try < $maxTries; $try++) {
    try {
        if(isset($decoded['message'])) {
            $stmt = $db->prepare('INSERT INTO messages (user_hash, name, message, timestamp) VALUES (:user_hash, :name, :message, datetime("now"))');
            $stmt->bindValue(':user_hash', $userHash, SQLITE3_TEXT);
            $stmt->bindValue(':name', $name, SQLITE3_TEXT);
            $stmt->bindValue(':message', $message, SQLITE3_TEXT);
            $stmt->execute();

            // If we reach here, it means the operation was successful
            break;
        }
    } catch (Exception $e) {
        // Log error or handle as necessary
        error_log("SQLite error: " . $e->getMessage());

        // Wait a short moment before retrying
        usleep(100000); // Wait for 100ms
    }
}

if ($try == $maxTries) {
    // Failed to write after retrying
    // Handle this case, maybe return an error message to the user or log
}