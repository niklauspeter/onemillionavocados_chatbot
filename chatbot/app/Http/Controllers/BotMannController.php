<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use GuzzleHttp\Client;

class BotMannController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');
   
        // Greet the user and instruct them to ask a question
        // $botman->hears('hi', function($botman) {
        //     $botman->reply('Hi! I\'ll be your guide in this avocado journey.');
        // });
   
        // Handle user questions
        $botman->hears('{message}', function($botman, $message) {
   
            // Process user input using Wit.ai
            $intent = $this->getWitAiIntent($message);
   
            // Retrieve response based on user intent
            $response = $this->getResponse($intent);
   
            // Reply to user
            $botman->reply($response);
            
            $botman->reply($intent);
   
        });
   
        $botman->listen();
    }

    private function getWitAiIntent($userInput)
    {
        $client = new Client();
        $response = $client->get('https://api.wit.ai/message', [
            'headers' => [
                'Authorization' => 'Bearer EVH3IP5KDFEZA3OVFD3P2HQWVK272ILA'
            ],
            'query' => [
                'q' => $userInput
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        // Extract intent from Wit.ai response
        return isset($data['intents'][0]['name']) ? $data['intents'][0]['name'] : null;
    }

    private function getResponse($intent)
    {
        // Load QA pairs from JSON file
        // $qaPairs = json_decode(file_get_contents(storage_path('app/qapairs.json')), true);

        // $qaPairs = include('qa_pairs.php');
        // Find matching question in QA pairs
        // foreach ($qaPairs as $qaPair) {
        //     if (isset($qaPair['question']) && strtolower($qaPair['question']) === strtolower($intent)) {
        //         return $qaPair['answer'];
        //     }
        // }

         // Fetch question-answer pairs from external file
         $qa_pairs = include('qa_pairs.php');
   
         // Check if the message matches any question in the list
         foreach ($qa_pairs as $question => $answer) {
             if (strcasecmp($intent, $question) === 0) {
                 return $answer;
             }
         }

        // If no direct match found, return a default response
        return "I'm sorry, I don't understand.";
    }
}
