let isFormValid = false;

// Open the first modal and display selected report reason
function openFirstModal(event) {
    event.preventDefault();

    if (validateForm()) {
        const reportReason = document.getElementById("report_reason").value;

        // Update the modal fields
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

// Validation function
function validateForm() {
    const requiredFields = document.querySelectorAll("#reportForm [required]");
    let isValid = true;

    requiredFields.forEach(field => {
        if (field.value.trim() === "") {
            isValid = false;
        }
    });

    return isValid;
}

// Attach event listener to the submit button
document.getElementById("submitBtn").addEventListener("click", openFirstModal);

// Enable the submit button when form fields are filled
document.querySelectorAll("#reportForm [required]").forEach(field => {
    field.addEventListener("input", function () {
        const submitBtn = document.getElementById("submitBtn");
        if (validateForm()) {
            submitBtn.classList.add("active");
            submitBtn.disabled = false;
        } else {
            submitBtn.classList.remove("active");
            submitBtn.disabled = true;
        }
    });
});
