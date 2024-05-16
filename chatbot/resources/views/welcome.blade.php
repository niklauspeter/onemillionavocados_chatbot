<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel 10 - Botman Chatbot</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
    </body>
   
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css">
    <script>
        var botmanWidget = {
            aboutText: 'Say hi back',
            introMessage: "Hi, i'm Silvano"
        };
    </script>
   <!-- Add this to your blade template -->
    
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
    <!-- Add this to your blade template -->
    <script src="{{ asset('js/botman-widget.js') }}"></script>
    
       
</html> 
