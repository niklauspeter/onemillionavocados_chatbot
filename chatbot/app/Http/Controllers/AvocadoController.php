<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Interfaces\CacheInterface;

class AvocadoController extends Controller implements CacheInterface
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \BotMan\BotMan\BotMan  $bot
     * @return void
     */
    public function handle(Request $request, BotMan $bot)
    {
        $bot->setCache(Cache::getStore()); // Set the cache store for BotMan

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
        
        $answer = $this->getCachedAnswer($question);

        if (!$answer) {
            // If answer is not cached, fetch it from the FAQ array
            $answer = $this->faq[$question] ?? 'I\'m sorry, I don\'t have an answer to that question.';
            $this->cacheAnswer($question, $answer);
        }
        
        Log::info('Sending answer: ' . $answer); // Log the answer being sent

        $bot->reply($answer);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Cache::get($key, $default);
    }

    /**
     * Store an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  \DateTimeInterface|\DateInterval|int|null  $ttl
     * @return bool
     */
    public function put($key, $value, $ttl = null)
    {
        return Cache::put($key, $value, $ttl);
    }

    /**
     * Determine if an item exists in the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return Cache::has($key);
    }

    /**
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return Cache::pull($key, $default);
    }


    private function getCachedAnswer($question)
    {
        // Retrieve answer from cache
        return $this->get('botman_answer_' . md5($question));
    }

    private function cacheAnswer($question, $answer)
    {
        // Cache the answer with an expiration time
        $this->put('botman_answer_' . md5($question), $answer, now()->addMinutes(30));
    }
}
