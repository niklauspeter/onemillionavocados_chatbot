<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\File;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('Hello BotMan!', function (BotMan $bot) {
            $bot->reply('Hello! How can I assist you today?');
            $bot->ask('What\'s your question?', function (Answer $answer, BotMan $bot) {
                $userQuestion = strtolower($answer->getText());

                // Read QA pairs from the JSON file
                $qaPairs = json_decode(File::get(public_path('qa_pairs.json')), true);

                // Check if the user's question matches any predefined question
                foreach ($qaPairs as $question => $response) {
                    if (strpos(strtolower($question), $userQuestion) !== false) {
                        $bot->reply($response);
                        return;
                    }
                }

                // If no match found, provide a default response
                $bot->reply('I apologize, but I don\'t have an answer for that question.');
            });
        });

        $botman->listen();
    }
}
