<?php

use App\Conversations\QuizConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Cache\LaravelCache;

$driver = DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
$config = [

    // "botman" => [
    //     'conversation_cache_time' => 720 ,
    //     'user_cache_time' => 720,
    // ],

    'telegram' => [
        'token' => '6076846904:AAHtzW0YDIPGNLkhn8-vFQNwuL9PfSATFR0'
    ]
];



// Create an instance
$botman = BotManFactory::create($config, new LaravelCache());





// $botman->hears('Hi', function (BotMan $bot) {
//     $bot->reply('Hello! write start for begin exam');
// });

$botman->hears('start', function (BotMan $bot) {
    $telegramUser = $bot->getUser();
    $telegramUserId = $telegramUser->getId();

    $bot->startConversation(new QuizConversation(), $telegramUserId);
})->stopsConversation();

$botman->listen();
