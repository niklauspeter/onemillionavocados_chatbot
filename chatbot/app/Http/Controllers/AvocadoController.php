<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class AvocadoController extends Controller
{
    // Define the questions and their corresponding answers
    private $faq = [
        'What is an orange?' => 'An orange is a citrus fruit.',
        'What are the benefits of eating oranges?' => 'Oranges are rich in vitamin C, fiber, and antioxidants. They can boost your immune system and promote overall health.',
        'How do you know if an orange is ripe?' => 'Ripe oranges are firm, heavy for their size, and have a bright orange color. They should also give slightly when squeezed.',
        // Add more questions and answers as needed
    ];

    /**
     * Handle incoming messages from the chatbot.
     *
     * @param  \BotMan\BotMan\BotMan  $bot
     * @return void
     */
    public function handle(Request $request, BotMan $bot)
    {
        
        $bot->hears('.*', function (BotMan $bot, $message) {
            $this->respondToMessage($bot, $message);
        });

        $bot->listen();
    }

    /**
     * Respond to an incoming message.
     *
     * @param  \BotMan\BotMan\BotMan  $bot
     * @param  \BotMan\BotMan\Messages\Incoming\IncomingMessage  $message
     * @return void
     */
    private function respondToMessage(BotMan $bot, IncomingMessage $message)
    {
        $question = $message->getText();
        

        if (isset($this->faq[$question])) {
            $answer = $this->faq[$question];
        } else {
            $answer = 'I\'m sorry, I don\'t have an answer to that question.';
        }
        

        $bot->reply($answer);
    }
}
