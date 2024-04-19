<?php

namespace App\Http\Controllers;
   
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
   
class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');
   
        // Greet the user and instruct them to ask a question
        $botman->hears('hi', function($botman) {
            $botman->reply('Hi! I\'m your lovely avocado assistant. Please ask your question.');
        });
   
        // Handle user questions
        $botman->hears('{message}', function($botman, $message) {
   
            // Fetch question-answer pairs from external file
            $qa_pairs = include('qa_pairs.php');
   
            // Check if the message matches any question in the list
            foreach ($qa_pairs as $question => $answer) {
                if (strcasecmp($message, $question) === 0) {
                    $botman->reply($answer);
                    return;
                }
            }
            
            // If no matching question found, provide a default response
            $botman->reply("I'm sorry, I don't understand that question.");
   
        });
   
        $botman->listen();
    }
}