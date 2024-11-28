// chat.js
// Sidebar toggle function
function toggleSidebar() {
    const body = document.body;
    const toggleButton = document.querySelector('.toggle-btn');

    body.classList.toggle('sidebar-visible');

    if (body.classList.contains('sidebar-visible')) {
        toggleButton.style.display = 'none';
    } else {
        toggleButton.style.display = 'inline-flex';
    }
}

document.addEventListener("DOMContentLoaded", loadChatHistory);

function sendMessage(event, senderRole) {
    event.preventDefault();
    const chatInput = document.getElementById("chatInput");
    const messageContents = chatInput.value.trim();
    if (!messageContents) return;

    // Add message
    addMessageToChat(messageContents, senderRole);
    chatInput.value = "";

    // Send message to server
    fetch("../database/chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `senderRole=${senderRole}&messageContents=${encodeURIComponent(messageContents)}`
    }).then(response => response.json())
      .then(data => {
          if (data.status === "success") {
              showNotification();

              // Auto-response after sending a message
              const autoResponse = senderRole === 'employer' ? 
                "Thank you for your message. I will get back to you soon!" : 
                "Thank you for your message. We will get back to you soon!";

              setTimeout(() => {
                  addMessageToChat(autoResponse, senderRole === "employer" ? "job_seeker" : "employer");

                  fetch("../database/chat.php", {
                      method: "POST",
                      headers: { "Content-Type": "application/x-www-form-urlencoded" },
                      body: `senderRole=${senderRole === "employer" ? "job_seeker" : "employer"}&messageContents=${encodeURIComponent(autoResponse)}`
                  }).then(response => response.json())
                    .then(data => { 
                        if (data.status !== "success") {
                            console.error(data.error);
                        }
                    });
              }, 1000);
          }
      });
}

function addMessageToChat(messageContents, senderRole, messageID = null) {
    const chatSection = document.getElementById("chatSection");
    const messageElement = document.createElement("div");
    messageElement.classList.add("chat-message", senderRole === "employer" ? "employer-message" : "job-seeker-message");
    messageElement.innerText = messageContents;

    // Set the alignment based on the sender's role
    messageElement.classList.add(senderRole === 'employer' ? 'align-left' : 'align-right');

    // Add edit and delete buttons only for the current user's message
    const currentUserRole = 'employer'; // Replace this with dynamic role (e.g., 'job_seeker' or 'employer')
    if (senderRole === currentUserRole) {
        const editButton = document.createElement("button");
        editButton.innerText = "Edit";
        editButton.classList.add("edit-button");
        editButton.onclick = () => editMessage(messageID, messageElement, currentUserRole);
        messageElement.appendChild(editButton);

        const deleteButton = document.createElement("button");
        deleteButton.innerText = "Delete";
        deleteButton.classList.add("delete-button");
        deleteButton.onclick = () => deleteMessage(messageID, messageElement, currentUserRole);
        messageElement.appendChild(deleteButton);
    }

    chatSection.appendChild(messageElement);
    chatSection.scrollTop = chatSection.scrollHeight;
}

function editMessage(messageID, messageElement, currentUserRole) {
    const newMessage = prompt("Edit your message:", messageElement.innerText.replace("Edit", "").replace("Delete", "").trim());
    
    if (newMessage && newMessage !== messageElement.innerText) {
        // Update message in the database
        fetch("../database/chat.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `senderRole=${currentUserRole}&messageContents=${encodeURIComponent(newMessage)}&messageID=${messageID}`
        }).then(response => response.json())
          .then(data => {
              if (data.status === "success") {
                  // Update the message in the UI
                  messageElement.innerText = newMessage;
                  const editButton = document.createElement("button");
                  editButton.innerText = "Edit";
                  editButton.classList.add("edit-button");
                  editButton.onclick = () => editMessage(messageID, messageElement, currentUserRole);
                  messageElement.appendChild(editButton);
                  
                  // Re-add delete button after editing
                  const deleteButton = document.createElement("button");
                  deleteButton.innerText = "Delete";
                  deleteButton.classList.add("delete-button");
                  deleteButton.onclick = () => deleteMessage(messageID, messageElement, currentUserRole);
                  messageElement.appendChild(deleteButton);
              } else {
                  alert("Failed to edit message.");
              }
          });
    }
}

function deleteMessage(messageID, messageElement, currentUserRole) {
    const confirmation = confirm("Are you sure you want to delete this message?");
    
    if (confirmation) {
        // Send delete request to the server
        fetch("../database/chat.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `senderRole=${currentUserRole}&messageID=${messageID}&delete=true`
        }).then(response => response.json())
          .then(data => {
              if (data.status === "success") {
                  // Remove the message from the UI
                  messageElement.remove();
              } else {
                  alert("Failed to delete message.");
              }
          });
    }
}

function loadChatHistory() {
    const currentUserRole = 'employer'; // Replace this with dynamic role (e.g., 'job_seeker' or 'employer')

    fetch("../database/chat.php")
        .then(response => response.json())
        .then(chats => {
            if (Array.isArray(chats)) {
                chats.forEach(chat => {
                    addMessageToChat(chat.messageContents, chat.senderRole, chat.id);
                });
            } else {
                console.error(chats.messageContents);
            }
        });
}

// Show notification
function showNotification() {
    const modal = document.getElementById("notificationModal");
    const overlay = document.getElementById("overlay");

    modal.style.display = "block";
    overlay.style.display = "block";
}

// Close notification
function closeNotification() {
    const modal = document.getElementById("notificationModal");
    const overlay = document.getElementById("overlay");

    modal.style.display = "none";
    overlay.style.display = "none";
}
