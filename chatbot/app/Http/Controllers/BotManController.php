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
   
        $botman->hears('{message}', function($botman, $message) {
   
            // Define your list of questions and corresponding answers
            $qa_pairs = [
                'What is your name?' => 'My name is ChatBot.',
                'How are you?' => 'I am a bot, so I don\'t have feelings, but thanks for asking!',
                'What can you do?' => 'I can answer your questions and provide assistance.',
                // Add more question-answer pairs as needed
            ];
   
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
