// Example: Modal open/close functionality
document.addEventListener('DOMContentLoaded', function() {
    // Open Modal
    const openModalButtons = document.querySelectorAll('[data-modal-target]');
    const closeModalButtons = document.querySelectorAll('[data-close-button]');
    const overlay = document.getElementById('overlay');

    openModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = document.querySelector(button.dataset.modalTarget);
            openModal(modal);
        });
    });

    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal');
            closeModal(modal);
        });
    });

    overlay.addEventListener('click', () => {
        const modals = document.querySelectorAll('.modal.active');
        modals.forEach(modal => closeModal(modal));
    });

    function openModal(modal) {
        if (modal == null) return;
        modal.classList.add('active');
        overlay.classList.add('active');
    }

    function closeModal(modal) {
        if (modal == null) return;
        modal.classList.remove('active');
        overlay.classList.remove('active');
    }
});

// Form validation example
function validateForm() {
    const transportName = document.querySelector('input[name="transport_name"]').value;
    const trackingNumber = document.querySelector('input[name="tracking_number"]').value;
    
    if (transportName.trim() === '' || trackingNumber.trim() === '') {
        alert('Please fill in all fields!');
        return false;
    }
    return true;
}
