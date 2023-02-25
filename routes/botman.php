<?php

use App\Conversations\QuizConversation;
use App\Conversations\SubjectConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Attachments\ButtonTemplate;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

$driver = DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
$config = [
    'telegram' => [
        'token' => env('TELEGRAM_TOKEN'),
    ]
];


$botman = BotManFactory::create($config, new LaravelCache());

$botman->hears('/start', function (BotMan $bot) {

    $bot->startConversation(new SubjectConversation);
});


$botman->listen();
