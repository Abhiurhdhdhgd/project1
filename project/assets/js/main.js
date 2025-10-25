// Simple JavaScript enhancements for the inventory system

// Confirm before deleting items
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation to delete buttons
    const deleteButtons = document.querySelectorAll('button[name="delete_item"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
    
    // Add real-time search functionality
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            // In a real implementation, this would filter the table
            // For now, we'll just show a message
            console.log('Searching for: ' + this.value);
        });
    }
    
    // Add form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Check if required fields are filled
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'red';
                } else {
                    field.style.borderColor = '#ddd';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });
});