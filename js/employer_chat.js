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


document.addEventListener("DOMContentLoaded", () => {
    // Address of the current window 
    address = window.location.search 
  
    // Returns a URLSearchParams object instance 
    parameterList = new URLSearchParams(address) 
    const jobSeekerID = parameterList.get("jobSeekerID"); 
    loadChatHistory(jobSeekerID);
});

function sendMessage(event, senderRole, userID) {
    event.preventDefault();
    const chatInput = document.getElementById("chatInput");
    const messageContents = chatInput.value.trim();
    if (!messageContents) return;

    // Add message to the chat section
    addMessageToChat(messageContents, senderRole);
    chatInput.value = "";

    address = window.location.search 
  
    // Returns a URLSearchParams object instance 
    parameterList = new URLSearchParams(address) 
    const jobSeekerID = parameterList.get("jobSeekerID"); 

    // Send message to the server
    fetch("../database/chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `userID=${userID}&senderRole=${senderRole}&messageContents=${encodeURIComponent(messageContents)}&jobSeekerID=${jobSeekerID}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            // Instead of showing notification, directly reload the page
            setTimeout(() => {
                // Auto-response after sending a message
                const autoResponse = senderRole === 'employer' ? 
                    "Thank you for your message. I will get back to you soon!" : 
                    "Thank you for your message. We will get back to you soon!";

                addMessageToChat(autoResponse, senderRole === "employer" ? "job_seeker" : "employer");

                // Send auto-response to the server
                fetch("../database/chat.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `senderRole=${senderRole === "employer" ? "job_seeker" : "employer"}&messageContents=${encodeURIComponent(autoResponse)}&jobSeekerID=${jobSeekerID}`
                })
                .then(response => response.json())
                .then(data => { 
                    if (data.status === "success") {
                        // Reload the page after sending the auto-response
                        location.reload(); // This will reload the page
                    } else {
                        console.error(data.error);
                    }
                });
            }, 1000);
        }
    });
}


// Show notification
function showNotification() {
    const modal = document.getElementById("notificationModal");
    const overlay = document.getElementById("overlay");

    modal.style.display = "block";
    overlay.style.display = "block";

    // Add click event to reload the page when OK is clicked
    const okButton = modal.querySelector("button");
    okButton.onclick = () => {
        closeNotification(); // Close the modal
        location.reload();   // Reload the page after closing the notification
    };
}

// Close notification
function closeNotification() {
    const modal = document.getElementById("notificationModal");
    const overlay = document.getElementById("overlay");

    modal.style.display = "none";
    overlay.style.display = "none";
}

let lastMessageDate = null; // Variable to store the last message date

function addMessageToChat(messageContents, senderRole, messageID = null, timestamp = null) {
    const chatSection = document.getElementById("chatSection");
    
    // Parse the timestamp if provided
    const messageDate = timestamp ? new Date(timestamp) : null;

    // Check if we need to add a date separator
    if (messageDate && (!lastMessageDate || messageDate.toDateString() !== lastMessageDate.toDateString())) {
        addDateSeparator(messageDate);
        lastMessageDate = messageDate; // Update the last message date
    }

    const messageElement = document.createElement("div");
    messageElement.classList.add("chat-message", senderRole === "employer" ? "employer-message" : "job-seeker-message");
    messageElement.innerText = messageContents;

    // Set the alignment based on the sender's role
    messageElement.classList.add(senderRole === 'employer' ? 'align-left' : 'align-right');

    // Get the current user role dynamically (replace with your method of retrieving the role)
    const currentUserRole = 'employer'; // This should be dynamically set based on logged-in user

    // Only add the ellipsis button for employer messages
    if (currentUserRole === 'employer' && senderRole === 'employer') {
        const ellipsisButton = document.createElement("button");
        ellipsisButton.innerText = "⋮"; // Three dots
        ellipsisButton.classList.add("ellipsis-button");
        ellipsisButton.onclick = () => toggleEditDeleteMenu(messageElement, messageID, senderRole);
        ellipsisButton.style.display = "none"; // Initially hide the button
        
        // Show the ellipsis button on hover
        messageElement.onmouseover = () => {
            ellipsisButton.style.display = "inline"; // Show the button on hover
        };
        messageElement.onmouseout = () => {
            ellipsisButton.style.display = "none"; // Hide the button when not hovering
        };

        // Append the button before the message text
        messageElement.prepend(ellipsisButton);
    }

    // Create a timestamp element
    if (timestamp) {
        const timestampElement = document.createElement("span");
        timestampElement.classList.add("message-timestamp");
        
        // Format the time to show only hours and minutes
        const options = { hour: '2-digit', minute: '2-digit', hour12: false }; // 24-hour format
        timestampElement.innerText = new Date(timestamp).toLocaleTimeString([], options);
        
        messageElement.appendChild(timestampElement); // Append timestamp to the message
    }

    chatSection.appendChild(messageElement);
    chatSection.scrollTop = chatSection.scrollHeight;
}


function addDateSeparator(date) {
    const chatSection = document.getElementById("chatSection");
    const dateElement = document.createElement("div");
    dateElement.classList.add("date-separator");
    dateElement.innerText = date.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
    
    chatSection.appendChild(dateElement);
}


function toggleEditDeleteMenu(messageElement, messageID, senderRole) {
    // Check if the menu already exists
    let menu = messageElement.querySelector(".edit-delete-menu");

    // If the menu doesn't exist, create it
    if (!menu) {
        menu = document.createElement("div");
        menu.classList.add("edit-delete-menu");

        // Create and append Edit button
        const editButton = document.createElement("button");
        editButton.innerText = "Edit";
        editButton.onclick = () => editMessage(messageID, messageElement, senderRole);
        menu.appendChild(editButton);

        // Create and append Delete button
        const deleteButton = document.createElement("button");
        deleteButton.innerText = "Delete";
        deleteButton.onclick = () => deleteMessage(messageID, messageElement, senderRole);
        menu.appendChild(deleteButton);

        messageElement.appendChild(menu); // Append the menu to the message element
    }

    // Toggle menu visibility
    if (menu.style.display === "block") {
        menu.style.display = "none"; // Hide the menu if it's already visible
    } else {
        // Show the menu
        menu.style.display = "block";

        // Close the menu when clicking anywhere else
        document.addEventListener("click", function handleClickOutside(event) {
            if (!messageElement.contains(event.target) && menu) {
                menu.style.display = "none"; // Hide the menu
                document.removeEventListener("click", handleClickOutside); // Remove listener after closing
            }
        });
    }
}



window.onclick = function(event) {
    const ellipsisButtons = document.querySelectorAll(".ellipsis-button");
    const menus = document.querySelectorAll(".edit-delete-menu");
    
    // Check if the click is not on any ellipsis button or open menu
    if (!event.target.matches('.ellipsis-button')) {
        menus.forEach(menu => {
            menu.style.display = "none"; // Close all menus
        });
    }
};

function editMessage(messageID, messageElement, senderRole, userID) {
    // Get the current message text without the ellipsis and time
    const originalMessage = messageElement.innerText.replace(" (edited)", "").split(" ").slice(0, -1).join(" "); // Assuming the last part is the time
    
    // Set the original message in the modal input
    const editMessageInput = document.getElementById("editMessageInput");
    editMessageInput.value = originalMessage; // Set the input value to the current message
    
    // Show the modal
    const editMessageModal = document.getElementById("editMessageModal");
    editMessageModal.style.display = "block";

    // Save the edit
    const saveEditButton = document.getElementById("saveEditButton");
    saveEditButton.onclick = () => {
        location.reload()
        const newMessage = editMessageInput.value.trim();
        if (newMessage && newMessage !== originalMessage) {
            // Update message in the database
            fetch("../database/chat.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `userID=${userID}&senderRole=${senderRole}&messageContents=${encodeURIComponent(newMessage)}&messageID=${messageID}`
            }).then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    // Update the message in the UI, appending the new time format
                    const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    messageElement.innerText = `${newMessage}`; // Add time and edited note
                    location.reload()
                    //   // Recreate edit and delete buttons
                    
                } else {
                    alert("Failed to edit message.");
                }
            });
        }
    };

    // Close the modal when the user clicks on <span> (x)
    const modalCloseBtn = document.getElementById("modalCloseBtn");
    modalCloseBtn.onclick = closeEditModal;
    
    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === editMessageModal) {
            closeEditModal();
        }
    };
}

function closeEditModal() {
    const editMessageModal = document.getElementById("editMessageModal");
    editMessageModal.style.display = "none"; // Hide the modal
}


let currentMessageIDToDelete = null; // Variable to hold the message ID to be deleted

function deleteMessage(messageID, messageElement, currentUserRole, userID) {

    
    currentMessageIDToDelete = messageID; // Store the message ID to delete
    const deleteMessageModal = document.getElementById("deleteMessageModal");
    deleteMessageModal.style.display = "block"; // Show the modal
    
    // Confirm delete button click event
    const confirmDeleteButton = document.getElementById("confirmDeleteButton");
    confirmDeleteButton.onclick = () => {
        location.reload(); // This will reload the page
        // Send delete request to the server
        fetch("../database/chat.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `userID=${userID}&senderRole=${currentUserRole}&messageID=${messageID}&delete=true`
        }).then(response => response.json())
          .then(data => {
              if (data.status === "success") {
                  // Change the message content to "deleted"
                  
                  messageElement.innerText = "deleted"; 

                  // Optionally, you can also disable the edit and delete buttons
                  const existingButtons = messageElement.querySelectorAll("button");
                  existingButtons.forEach(button => button.remove()); // Remove buttons or disable them
                  location.reload(); // This will reload the page
                 
              } else {
                  alert("Failed to delete message.");
              }
          });
    };

    // Cancel button click event
    const cancelDeleteButton = document.getElementById("cancelDeleteButton");
    cancelDeleteButton.onclick = closeDeleteModal;

    // Close the modal when the user clicks on <span> (x)
    const modalCloseBtn = document.getElementById("deleteModalCloseBtn");
    modalCloseBtn.onclick = closeDeleteModal;

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === deleteMessageModal) {
            closeDeleteModal();
        }
    };
}

function closeDeleteModal() {
    const deleteMessageModal = document.getElementById("deleteMessageModal");
    deleteMessageModal.style.display = "none"; // Hide the modal
    currentMessageIDToDelete = null; // Reset the message ID to delete
}


function loadChatHistory(jobSeekerID) {
    var url = `../database/chat.php?jobSeekerID=${jobSeekerID}`
    fetch(url)
        .then(response => response.json())
        .then(chats => {
            if (Array.isArray(chats)) {
                chats.forEach(chat => {
                    addMessageToChat(chat.messageContents, chat.senderRole, chat.id, chat.timestamp); // Pass timestamp
                });
            } else {
                console.error(chats.messageContents);
            }
        });
}

function toggleDropdown() {
    const dropdownMenu = document.getElementById("dropdownMenu");
    const arrow = document.querySelector(".arrow");
    const arrowRect = arrow.getBoundingClientRect(); // Get position of the arrow

    // Position dropdown menu directly below the arrow
    dropdownMenu.style.top = `${arrowRect.bottom}px`; // Position dropdown below the arrow
    dropdownMenu.style.left = `${arrowRect.left}px`; // Align dropdown with arrow

    // Toggle the display of the dropdown
    dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
}

