<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use GuzzleHttp\Client;
use BotMan\Drivers\Facebook\FacebookDriver;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\BotManFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BotMannController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle(Request $request)
    {
        // $botman = app('botman');
        // Load the Facebook driver
        

        Log::info('Incoming request', ['request' => $request->all()]);
        // Check for the Facebook webhook verification request
        // if ($request->isMethod('get') && $request->has('hub_mode') && $request->input('hub_mode') === 'subscribe') {
        //     return response($request->input('hub_challenge'), 200)
        //         ->header('Content-Type', 'text/plain');
        // }
        
        if ($request->isMethod('get') && $request->has('hub_mode') && $request->input('hub_mode') === 'subscribe') {
            if ($request->input('hub_verify_token') === env('FACEBOOK_VERIFICATION')) {
                return response($request->input('hub_challenge'), 200)
                    ->header('Content-Type', 'text/plain');
            } else {
                return response('Verification token mismatch', 403)
                    ->header('Content-Type', 'text/plain');
            }
        }
    

        try {
            $config = [
                'facebook' => [
                    'token' => env('FACEBOOK_TOKEN'),
                    'app_secret' => env('FACEBOOK_APP_SECRET'),
                    'verification' => env('FACEBOOK_VERIFICATION'),
                ],
            ];
            DriverManager::loadDriver(FacebookDriver::class);
            
            // Create and return the BotMan instance
            // $config = config('botman.facebook'); // Load configuration from config/botman/facebook.php
            $botman = BotManFactory::create($config);
            if (!$botman->getDriver()->getName() === FacebookDriver::class) {
                throw new \Exception('Facebook driver is not loaded');
            }
    
            Log::info('Facebook driver is loaded');
            Log::info('Raw incoming request', ['request' => $request->getContent()]);
            
            $entries = $request->input('entry', []);
            $messageData = ''; // Define an empty messageData variable outside the loop
            foreach ($entries as $entry) {
            $messagings = $entry['messaging'] ?? [];
            foreach ($messagings as $messaging) {
                $messageData = $messaging['message']['text'] ?? '';
            }
        }
            Log::info('Extracted message data', ['messageData' => $messageData]);
            // Check if messageData is not empty before processing
            if (!empty($messageData)) {
                // Define the hears callback
                $botman->hears($messageData, function (BotMan $botman) use ($messageData) {
                    Log::info('Heard message', ['message' => $messageData]);

                    if (!preg_match('/^(hi|hey|hello)$/i', $messageData)) {
                        $intent = $this->getWitAiIntent($messageData);
                        if ($intent) {
                            $responses = $this->getResponse($intent);

                            foreach ($responses as $response) {
                                $botman->reply($response);
                            }
                        } else {
                            $botman->reply("I'm sorry, I couldn't understand that. Could you please rephrase?");
                        }
                    } else {
                        $botman->reply('Hi! Again, My name is Silvano I\'ll be your guide in this avocado journey.');
                        $botman->reply('Ask me all about avocados');
                    }
                });

                // Listen for the message
                $botman->listen();
            } else {
                Log::error('No message data found in the request');
            }
        } catch (\Exception $e) {
            Log::error('Error handling the request', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
             // Manually extract the message from the request
    //         $entries = $request->input('entry', []);
            
    //         foreach ($entries as $entry) {
    //             $messagings = $entry['messaging'] ?? [];
    //             foreach ($messagings as $messaging) {
    //                 $messageData = $messaging['message']['text'] ?? '';

    //                 Log::info('Extracted message data', ['messageData' => $messageData]);
    //                 // $botman->hears('{message}', function($botman, $message) {
    //                 //                 Log::info('Incoming request', ['message' => $message]);
    //                 //                 // Process user input using Wit.ai
    //                 //                 if (!preg_match('/^(hi|hey|hello)$/i', $message))
    //                 //                 {
    //                 if (!empty($messageData)) {
    //                     // Define the hears callback outside the loop
    //                     $botman->hears($messageData, function (BotMan $botman) use ($messageData) {
    //                         Log::info('Heard message', ['message' => $messageData]);
    //                         $botman->reply("Received: $messageData");
                            
    //                         if (!preg_match('/^(hi|hey|hello)$/i', $messageData)) {
    //                             $intent = $this->getWitAiIntent($messageData);
    //                             if ($intent) {
    //                                 $responses = $this->getResponse($intent);
    
    //                                 foreach ($responses as $response) {
    //                                     $botman->reply($response);
    //                                 }
    //                             } else {
    //                                 $botman->reply("I'm sorry, I couldn't understand that. Could you please rephrase?");
    //                             }
    //                         } else {
    //                             $botman->reply('Hi! Again, My name is Silvano I\'ll be your guide in this avocado journey.');
    //                             $botman->reply('Ask me all about avocados');
    //                         }
    //                     });
    
    //                     // Explicitly call listen to process the message
    //                     $botman->listen();
    //                 } else {
    //                     Log::error('No message data found in the request');
    //                 }
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Error handling the request', ['error' => $e->getMessage()]);
    //         return response()->json(['error' => 'Internal Server Error'], 500);
    //     }
    // }

            // // Greet the user and instruct them to ask a question
            // $botman->hears('^(hi|hey|hello)', function($botman) {
            //     logger()->info('Received "hi" message');
            //     $botman->reply('Hi! Again, My name is Silvano I\'ll be your guide in this avocado journey.');
            //     $botman->reply('Ask me all about avocados');
            //     $botman->reply('Some topics i han help answer include:
            //     Propagation(grafting),
            //     Transplanting,
            //     Harvesting,
            //     Fertilization,
            //     Pests and Diseases,
            //     Haas avocado Variety,
            //     Avocado growth cycle,
            //     All about Haas Avocados,
            //     requirements for cultivating avocados,
            //     environmental conditions for Haas avocados,
            //     Farming Tools and pruning,
            //     Role of Mulching and tools to use,
            //     specialized irrigation tools and techniques,
            //     And avocado companion plants');
            //     // $this->spellOutResponse($botman, 'Hi! I\'ll be your guide in this avocado journey.');
            //     // $this->spellOutResponse($botman, 'Ask me about avocados');
        
            // });

            
            
    //         // Handle user questions
    //         $botman->hears('{message}', function($botman, $message) {
    //             Log::info('Incoming request', ['message' => $message]);
    //             // Process user input using Wit.ai
    //             if (!preg_match('/^(hi|hey|hello)$/i', $message))
    //             {
    //                 $intent = $this->getWitAiIntent($message);

    //                 // Retrieve response based on user intent
    //                 $responses = $this->getResponse($intent);

    //                 // Reply to user
    //                 foreach ($responses as $response) {
    //                     $botman->reply($response);
    //                 }
    //                 // foreach ($responses as $response) {
    //                 //     $this->spellOutResponse($botman, $response);
    //                 // }
    //                 // if (empty($responses)) {
    //                 //     $this->spellOutResponse($botman, $intent);
    //                 // }
    //             // $botman->reply($response);
                
    //             // $botman->reply($intent);
    //         }
    //             // $botman->reply('Hi! I\'ll be your guide in this avocado journey.');
    //             // $botman->reply('Ask me about avocados');
    //         });
    
    //         $botman->listen();
    //     } 
    //     catch (\Exception $e) {
    //         Log::error('Error handling the request', ['error' => $e->getMessage()]);
    //         return response()->json(['error' => 'Internal Server Error'], 500);
    //     }
    // }
    // private function spellOutResponse($botman, $message)
    // {
    //     $accumulatedResponse = '';
    //     $length = strlen($message);
        
    //     // Loop through each character and accumulate the response
    //     for ($i = 0; $i < $length; $i++) {
    //         $accumulatedResponse .= $message[$i];
    //         usleep(000000); // Delay to simulate typing effect (100000 microseconds = 0.1 seconds)
    //     }

    //     // Send the accumulated response as a single message
    //     $botman->reply($accumulatedResponse);
    // }
    // private function spellOutResponse($botman, $message, $index = 0)
    // {
    //     if ($index < strlen($message)) {
    //         $botman->reply(substr($message, $index, 1));
    //         sleep(1); // Adjust delay as needed
    //         $this->spellOutResponse($botman, $message, $index + 1);
    //     }
    // }

    // private function spellOutResponse($botman, $message)
    // {
    //     $this->spellOut($botman, $message, 0, '');
    // }

    // private function spellOut($botman, $message, $index, $accumulatedResponse)
    // {
    //     if ($index < strlen($message)) {
    //         $accumulatedResponse .= $message[$index];
    //         $index++;
    //         // Delay 0.1 seconds between each character
    //         usleep(010000); // 100000 microseconds = 0.1 seconds
            
    //         $this->spellOut($botman, $message, $index, $accumulatedResponse);
    //     } else {
    //         $botman->reply($accumulatedResponse);
    //     }
    // }

    private function getWitAiIntent($userInput)
    {
        Log::info('Wit.ai request', ['userInput' => $userInput]);

        if (empty($userInput)) {
            Log::error('Empty message received for Wit.ai request');
            return null;
        }
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
        // Fetch question-answer pairs from external file
        $qa_pairs = include('qa_pairs.php');
        $responses = [];
       
        // Check if the intent matches any question in the list
        foreach ($qa_pairs as $question => $answer) {
            if (strcasecmp($intent, $question) === 0) {
                array_push($responses, $answer);
            }
        }
        logger()->info('Responses:', $responses);
        
        // If matches are found, return the responses
        if (!empty($responses)) {
            return $responses;
        }
        
        // If no direct match found, return a default response
        return ["I'm sorry, I don't understand.
        Kindly rephrase your question"];
    }
    
}
