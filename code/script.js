document.addEventListener('DOMContentLoaded', function() {
    focusMessageInput();
    setInterval(fetchMessages, 1000); // Poll every second
	loadUserName(); // Load and prepopulate the user name if it exists in localStorage
});

document.getElementById("messageForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const nameInput = document.getElementById("nameInput");
    const messageInput = document.getElementById("messageInput");
    sendMessage(nameInput.value, messageInput.value);
    saveUserName(nameInput.value); // Save the user name after sending a message
    messageInput.value = ''; // Clear message input after sending
    focusMessageInput(); // Refocus on the message input
});

function sendMessage(name, message) {
    fetch('postMessage.php', {
        method: 'POST',
        body: JSON.stringify({ name: name, message: message }),
        headers: { 'Content-Type': 'application/json' }
    });
}

// Function to save the user name to localStorage
function saveUserName(name) {
    localStorage.setItem('userName', name);
}

// Function to load the user name from localStorage and prepopulate the name field
function loadUserName() {
    const nameInput = document.getElementById("nameInput");
    const storedName = localStorage.getItem('userName');
    if (storedName) {
        nameInput.value = storedName;
    }
}

function fetchMessages() {
    fetch('getMessages.php')
    .then(response => response.json())
    .then(data => {
        data.messages.forEach(msg => {
            const msgHash = btoa(msg.message + msg.timestamp).substring(0, 10);
            if (!document.getElementById(msgHash)) {
                displayMessage(msg, msgHash);
            }
        });
    });
}

function displayMessage(msg, msgHash) {
    const messageArea = document.getElementById('messageArea');
    const div = document.createElement('div');
    div.id = msgHash;
    div.className = 'message-box';

    const detailsDiv = document.createElement('div');
    detailsDiv.innerHTML = `${msg.name || 'Anonymous'} @ ${msg.timestamp}`;
    detailsDiv.className = "message-details";

    const messageTextDiv = document.createElement('div');
    messageTextDiv.className = "message-text";
    messageTextDiv.textContent = msg.message;

    // Convert user_hash into a color and apply it as the border color of the message box
    const borderColor = stringToColor(msg.user_hash);
    div.style.border = `4px solid ${borderColor}`;

    div.appendChild(detailsDiv);
    div.appendChild(messageTextDiv);
    messageArea.appendChild(div);
	focusMessageInput(); // Refocus on the message input
}

function focusMessageInput() {
    const messageInput = document.getElementById("messageInput");
    messageInput.focus();
    messageInput.scrollIntoView({ behavior: 'smooth', block: 'center' }); // 
}

// Utility function to generate a consistent hexadecimal color from a string
function stringToColor(str) {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    const color = '#' + ((hash & 0x00FFFFFF).toString(16).padStart(6, '0'));
    return color;
}