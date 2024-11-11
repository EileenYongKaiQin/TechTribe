let currentMessageId = null; // Track the ID of the message being edited or deleted

// Sidebar toggle function
function toggleSidebar() {
    const body = document.body;
    const toggleButton = document.querySelector('.toggle-btn');
    body.classList.toggle('sidebar-visible');
    toggleButton.style.display = body.classList.contains('sidebar-visible') ? 'none' : 'inline-flex';
}

document.addEventListener("DOMContentLoaded", loadChatHistory);

function sendMessage(event, senderRole) {
    event.preventDefault();
    const chatInput = document.getElementById("chatInput");
    const message = chatInput.value.trim();
    if (!message) return;

    addMessageToChat(message, senderRole);
    chatInput.value = "";

    fetch("../database/chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `sender_role=${senderRole}&message=${encodeURIComponent(message)}`
    }).then(response => response.json())
      .then(data => {
          if (data.status === "success") {
              showNotification();
          }
      });
}

function addMessageToChat(message, senderRole, messageId = null) {
    const chatSection = document.getElementById("chatSection");
    const messageElement = document.createElement("div");
    messageElement.classList.add("chat-message", senderRole === "employer" ? "employer-message" : "job-seeker-message");
    messageElement.innerText = message;
    
    if (senderRole === "employer" && messageId) {
        messageElement.setAttribute("data-id", messageId);
        
        const editButton = document.createElement("button");
        editButton.innerText = "Edit";
        editButton.onclick = () => openEditModal(messageId, message);
        messageElement.appendChild(editButton);
        
        const deleteButton = document.createElement("button");
        deleteButton.innerText = "Delete";
        deleteButton.onclick = () => deleteMessage(messageId);
        messageElement.appendChild(deleteButton);
    }
    
    chatSection.appendChild(messageElement);
    chatSection.scrollTop = chatSection.scrollHeight;
}

function openEditModal(messageId, currentMessage) {
    currentMessageId = messageId;
    document.getElementById("editMessageInput").value = currentMessage;
    document.getElementById("editModal").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function saveEditedMessage() {
    const newMessageContent = document.getElementById("editMessageInput").value.trim();
    if (!newMessageContent) return;

    fetch("../database/chat.php", {
        method: "PUT",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `message_id=${currentMessageId}&message=${encodeURIComponent(newMessageContent)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            const messageElement = document.querySelector(`[data-id='${currentMessageId}']`);
            messageElement.firstChild.nodeValue = newMessageContent;
            closeEditModal();
        } else {
            console.error(data.message);
        }
    });
}

function deleteMessage(messageId) {
    if (!confirm("Are you sure you want to delete this message?")) return;

    fetch("../database/chat.php", {
        method: "DELETE",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `message_id=${messageId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            const messageElement = document.querySelector(`[data-id='${messageId}']`);
            messageElement.remove();
        } else {
            console.error(data.message);
        }
    });
}

function loadChatHistory() {
    fetch("../database/chat.php")
        .then(response => response.json())
        .then(chats => {
            if (Array.isArray(chats)) {
                chats.forEach(chat => addMessageToChat(chat.message, chat.sender_role, chat.id));
            } else {
                console.error("Failed to load chat history:", chats.message);
            }
        });
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}

function showNotification() {
    document.getElementById("notificationModal").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function closeNotification() {
    document.getElementById("notificationModal").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}
