import './bootstrap';
import 'chart.js';

// Close flash messages
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');
    
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.remove();
        }, 5000);
        
        const closeButton = message.querySelector('.close-button');
        if (closeButton) {
            closeButton.addEventListener('click', () => {
                message.remove();
            });
        }
    });
});