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
        'token' => '6258565204:AAEmuU3WAOZ6k8R6qCFKYkhZeeTf-6j6VIk',
    ]
];
$botman = BotManFactory::create($config, new LaravelCache());



$botman->hears('Hi', function (BotMan $bot) {
    $bot->reply('Hello! write start for begin exam');
});


$botman->hears('/start', function (BotMan $bot) {

    $bot->startConversation(new SubjectConversation);
});


$botman->listen();
