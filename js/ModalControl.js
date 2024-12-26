let isFormValid = false;

// Open the first modal and display selected report reason
function openFirstModal() {
    const form = document.getElementById("reportForm");
    
    // Force native HTML5 validation popup if fields are empty
    if (!form.checkValidity()) {
        form.reportValidity();  // Trigger HTML5 validation
    } else {
        const reportReason = document.getElementById("report_reason").value;
        document.getElementById("reportReasonText").textContent = reportReason;
        document.getElementById("firstModal").style.display = "block";
    }
}

// Close the first modal
function closeFirstModal() {
    document.getElementById("firstModal").style.display = "none";
}

// Handle the "Submit" button in the first modal
function submitFirstModal() {
    closeFirstModal();
    document.getElementById("secondModal").style.display = "block";
    isFormValid = true; // Ensure that the form is submitted only once
}

// Close the second modal and submit the form
function finalSubmit() {
    if (isFormValid) {
        document.getElementById("reportForm").submit();
    } else {
        alert("Please fill out the form and review it before submitting.");
    }
}

// Close the second modal without submission
function closeSecondModal() {
    document.getElementById("secondModal").style.display = "none";
}

// Attach event listener to the submit button
document.getElementById("submitBtn").addEventListener("click", openFirstModal);


