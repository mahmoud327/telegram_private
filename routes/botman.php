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
    $message = 'أهلًا وسهلّا بك في البوت التطوعي لخدمة وتنظيم قروبات الواتس بالكلية بواسطة الطالب مبارك الدوسري

    لاي اضافة او تعديل على الروابط او المواد يرجى التواصل
    Telegram / http://t.me/Laravelmah_bot




    🔵 في حال رابط القروب ماطلع لك يعني الى الان ماوصلني

    🔵 اذا عندك الرابط ارسله لي على التليقرام';


    $keyboard = Keyboard::create()
                    ->oneTimeKeyboard()
                    ->type(Keyboard::TYPE_KEYBOARD)
                    ->resizeKeyboard(true)
                    ->addRow(KeyboardButton::create(SubjectConversation::Main_Menu_BUTTON));

    $bot->reply($message, $keyboard->toArray());

});


$botman->hears(SubjectConversation::Main_Menu_BUTTON, function (BotMan $bot) {
    $bot->startConversation(new SubjectConversation);
});

$botman->listen();
