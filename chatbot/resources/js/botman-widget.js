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

    // Open the widget automatically
    botmanWidget.open();
});
