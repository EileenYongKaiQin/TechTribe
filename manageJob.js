document.getElementById("applyButton").addEventListener("click", function() {
    Swal.fire({
        title: "Are you sure you want to apply for the job?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, apply",
        cancelButtonText: "No, cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with application
            fetch('/applyForJob', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire("Success!", data.message || "Application submitted successfully!", "success");
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire("Error", "Failed to apply for the job. Please try again later.", "error");
            });
        } else {
            Swal.fire("Cancelled", "Application canceled.", "info");
        }
    });
});