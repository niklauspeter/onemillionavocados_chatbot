<?php
namespace App\Http\Controllers;
   
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
   
class BotmanController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');
   
        $botman->hears('{message}', function($botman, $message) {
   
            if ($message == 'hi' || $message == 'Hi'|| $message == 'hello'|| $message == 'Hello' || $message == 'HELLO' || $message == 'HI') {
                $this->askName($botman);
            }
            
            else{
                $botman->reply("Start a conversation by saying hi.");
            }
   
        });
   
        $botman->listen();
    }
   
    /**
     * Place your BotMan logic here.
     */
    public function askName($botman)
    {
        $botman->ask('Hello! What is your Name?', function(Answer $answer, $conversation) {
   
            $name = $answer->getText();
   
            $this->say('Nice to meet you '.$name);

            $conversation->ask('can you share your email', function(Answer $answer, $conversation){
                $email = $answer->getText();
                $this->say('Email:'.$email);
            
                $conversation->ask('confirm if the above email is correct, reply with yes or no', function(Answer $answer, $conversation){
                    $confirmemail = $answer->getText();
                    if ($answer == 'yes' || $answer == 'Yes' ) {
                        $this->say('we have your details');
                    }
                    else{
                        $conversation->ask('can you share your email', function(Answer $answer, $conversation){
                            $email = $answer->getText();
                            $this->say('Email:'.$email);
                        });
                    }
                });
            });
        });
    }
}
