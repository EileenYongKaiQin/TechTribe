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
            })
            .then(data => {
                Swal.fire("Success!", data.message || "Application submitted successfully!", "success");
            })
            
        } else {
            Swal.fire("Cancelled", "Application canceled.", "info");
        }
    });
});
