function previewFiles() {
    const preview = document.getElementById('file-preview');
    const files = document.getElementById('evidence').files;
    
    // Clear the preview area before adding new files
    preview.innerHTML = '';

    Array.from(files).forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.classList.add('file-item');

        const link = document.createElement('a');
        link.target = "_blank"; // Open in a new tab
        link.href = URL.createObjectURL(file);
        link.onclick = () => setTimeout(() => URL.revokeObjectURL(link.href), 1000); // Free memory after use
        
        if (file.type.startsWith('image/')) {
            // Show a thumbnail preview for images
            const img = document.createElement('img');
            img.src = link.href;
            link.appendChild(img);
        } else {
            // Show file name for non-image files
            const fileName = document.createElement('span');
            fileName.textContent = file.name;
            link.appendChild(fileName);
        }

        fileItem.appendChild(link);

        // Add a remove icon
        const removeIcon = document.createElement('span');
        removeIcon.classList.add('remove-icon');
        removeIcon.innerHTML = '&times;';
        removeIcon.onclick = () => removeFile(index);
        fileItem.appendChild(removeIcon);

        preview.appendChild(fileItem);
    });
}

function removeFile(index) {
    const fileInput = document.getElementById('evidence');
    const dataTransfer = new DataTransfer();

    // Re-add all files except the one to remove
    Array.from(fileInput.files).forEach((file, i) => {
        if (i !== index) {
            dataTransfer.items.add(file);
        }
    });

    // Update the file input with new files list
    fileInput.files = dataTransfer.files;
    previewFiles(); // Refresh the preview
}


