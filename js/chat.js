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
    const message = chatInput.value.trim();
    if (!message) return;

    // Add message
    addMessageToChat(message, senderRole);
    chatInput.value = "";

    // Send message to server
    fetch("chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `sender_role=${senderRole}&message=${encodeURIComponent(message)}`
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

                  fetch("chat.php", {
                      method: "POST",
                      headers: { "Content-Type": "application/x-www-form-urlencoded" },
                      body: `sender_role=${senderRole === "employer" ? "job_seeker" : "employer"}&message=${encodeURIComponent(autoResponse)}`
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

function addMessageToChat(message, senderRole) {
    const chatSection = document.getElementById("chatSection");
    const messageElement = document.createElement("div");
    messageElement.classList.add("chat-message", senderRole === "employer" ? "employer-message" : "job-seeker-message");
    messageElement.innerText = message;
    chatSection.appendChild(messageElement);
    chatSection.scrollTop = chatSection.scrollHeight;
}

function loadChatHistory() {
    fetch("chat.php")
        .then(response => response.json())
        .then(chats => {
            if (Array.isArray(chats)) {
                chats.forEach(chat => addMessageToChat(chat.message, chat.sender_role));
            } else {
                console.error(chats.message);
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
