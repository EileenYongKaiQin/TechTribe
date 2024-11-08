// Attach event listener to the submit button
document.getElementById("submitBtn").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent the form from submitting immediately
    openFirstModal();       // Show the first modal
});

// Open the first modal and display selected report reason
function openFirstModal() {
    const reportReason = document.getElementById("report_reason").value;
    document.getElementById("reportReasonText").textContent = reportReason;
    document.getElementById("firstModal").style.display = "block";
}

// Close the first modal
function closeFirstModal() {
    document.getElementById("firstModal").style.display = "none";
}

// Handle the "Submit" button in the first modal
function submitFirstModal() {
    closeFirstModal();
    document.getElementById("secondModal").style.display = "block";
}

// Close the second modal and submit the form
function finalSubmit() {
    closeSecondModal();
    document.getElementById("reportForm").submit(); // Submit the form
}

// Close the second modal without submission
function closeSecondModal() {
    document.getElementById("secondModal").style.display = "none";
}

// Attach finalSubmit to the "Done" button in the second modal
document.querySelector('#finalSubmitBtn').addEventListener("click", finalSubmit);