// botman-widget.js

// Wait for the page to load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the BotMan widget
    var botmanWidget = BotMan.init({
        introMessage: "Hi! I'm your lovely assistant. Please ask your question.",
        chatServer: '/botman',
        title: 'Chat with our Bot',
        mainColor: '#FF5722',
        bubbleBackground: '#FF5722',
        aboutText: '',
        bubbleAvatarUrl: '' // Add your avatar URL if needed
    });

    // Add shake animation to the widget container
    var widgetContainer = document.querySelector('.widget-container');
    widgetContainer.style.animation = 'shake 0.5s infinite'; // Adjust animation duration and timing if needed

    // Define the shake animation dynamically
    var style = document.createElement('style');
    style.innerHTML = `
        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }
    `;
    document.head.appendChild(style);
});
