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

    // Add message to chat UI
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

    // Set alignment based on sender role
    messageElement.classList.add(senderRole === 'employer' ? 'align-left' : 'align-right');

    // Attach message ID to the element
    if (messageID) {
        messageElement.setAttribute("data-message-id", messageID);
    }

    const currentUserRole = 'job_seeker'; // Should dynamically reflect logged-in user

    if ((currentUserRole === 'employer' && senderRole === 'employer') ||
        (currentUserRole === 'job_seeker' && senderRole === 'job_seeker')) {
        const editButton = document.createElement("button");
        editButton.innerText = "Edit";
        editButton.classList.add("edit-button");
        editButton.onclick = () => editMessage(messageID, messageElement, senderRole);
        messageElement.appendChild(editButton);

        const deleteButton = document.createElement("button");
        deleteButton.innerText = "Delete";
        deleteButton.classList.add("delete-button");
        deleteButton.onclick = () => deleteMessage(messageID, messageElement, senderRole);
        messageElement.appendChild(deleteButton);
    }

    chatSection.appendChild(messageElement);
    chatSection.scrollTop = chatSection.scrollHeight;
}

function editMessage(messageID, messageElement, senderRole) {
    if (!messageID) {
        console.error("Message ID is missing!");
        return;
    }

    const newMessage = prompt("Edit your message:", messageElement.innerText.replace("Edit", "").replace("Delete", "").trim());

    if (newMessage && newMessage !== messageElement.innerText) {
        fetch("../database/chat.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `senderRole=${senderRole}&messageContents=${encodeURIComponent(newMessage)}&messageID=${messageID}`
        }).then(response => response.json())
          .then(data => {
              if (data.status === "success") {
                  messageElement.innerText = newMessage + " (edited)";
                  recreateButtons(messageElement, messageID, senderRole);
              } else {
                  alert("Failed to edit the message.");
              }
          });
    }
}

function deleteMessage(messageID, messageElement, currentUserRole) {
    if (!messageID) {
        console.error("Message ID is missing!");
        return;
    }

    const confirmation = confirm("Are you sure you want to delete this message?");
    if (confirmation) {
        fetch("../database/chat.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `senderRole=${currentUserRole}&messageID=${messageID}&delete=true`
        }).then(response => response.json())
          .then(data => {
              if (data.status === "success") {
                  messageElement.innerText = "deleted";
                  const existingButtons = messageElement.querySelectorAll("button");
                  existingButtons.forEach(button => button.remove());
              } else {
                  alert("Failed to delete the message.");
              }
          });
    }
}

function recreateButtons(messageElement, messageID, senderRole) {
    const existingButtons = messageElement.querySelectorAll("button");
    existingButtons.forEach(button => button.remove());

    const editButton = document.createElement("button");
    editButton.innerText = "Edit";
    editButton.classList.add("edit-button");
    editButton.onclick = () => editMessage(messageID, messageElement, senderRole);
    messageElement.appendChild(editButton);

    const deleteButton = document.createElement("button");
    deleteButton.innerText = "Delete";
    deleteButton.classList.add("delete-button");
    deleteButton.onclick = () => deleteMessage(messageID, messageElement, senderRole);
    messageElement.appendChild(deleteButton);
}

function loadChatHistory() {
    const currentUserRole = 'job_seeker';

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

function showNotification() {
    const modal = document.getElementById("notificationModal");
    const overlay = document.getElementById("overlay");

    modal.style.display = "block";
    overlay.style.display = "block";
}

function closeNotification() {
    const modal = document.getElementById("notificationModal");
    const overlay = document.getElementById("overlay");

    modal.style.display = "none";
    overlay.style.display = "none";
}
