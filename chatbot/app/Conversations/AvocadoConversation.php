<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\IncomingMessage;
use BotMan\BotMan\Questions\Question;
use BotMan\BotMan\Replies\Conversation;
use BotMan\BotMan\Replies\Reply;

class AvocadoConversation extends Conversation
{
    protected $questions = [
        // Pre-written questions and answers
        "What is an orange?" => "An orange is a citrus fruit known for its sweet, tangy flavor and vibrant orange color.",
        "What are the health benefits of oranges?" => "Oranges are a rich source of Vitamin C, which helps boost the immune system. They also contain fiber and antioxidants.",
        "What are different varieties of oranges?" => "There are many varieties, including navel oranges, Valencia oranges, and blood oranges. Each has unique flavor and characteristics.",
        "How to select a good orange?" => "Look for oranges with firm, smooth skin and a bright color. Avoid soft or bruised fruits.",
    ];

    public function run()
    {
        $this->askTheQuestion();
    }

    protected function askTheQuestion()
    {
        $question = Question::create("What would you like to know about oranges?")
            ->fallback("I didn't understand. Please try asking a different question about oranges.");

        $this->ask($question, function (Answer $answer) {
            $userQuestion = $answer->getText();

            if (array_key_exists($userQuestion, $this->questions)) {
                $this->say($this->questions[$userQuestion]);
            } else {
                $this->say("I don't know the answer to that question yet. But I'm always learning!");
            }

            $this->bot->startConversation(new OrangeConversation());
        });
    }
}
