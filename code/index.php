<?php

if (!file_exists('messages.db')){
	require "setup.php";
	exit;
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no,initial-scale=1">
    <title>Town Square</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="messageArea"></div>
    <form id="messageForm">
		<input type="text" id="nameInput" placeholder="Your name..." />
		<input type="text" id="messageInput" placeholder="Type your message here..." />
		<button type="submit">Send</button>
	</form>

    <script src="script.js"></script>
</body>
</html>