<?php

namespace App\Conversations;

use App\Models\Course;
use App\Models\Material;
use App\Models\Section;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class SubjectConversation extends Conversation
{
    const BACK = '🔙 Back';
    const Main_Menu= '🔝 Main Menu';

    private $backAndMainButtons = [self::BACK, self::Main_Menu];
    protected $lastCourse;
    protected $lastMaterial;

    public function run()
    {
        $message = 'أهلًا وسهلّا بك في البوت التطوعي لخدمة وتنظيم قروبات الواتس بالكلية
 
                    لاي اضافة او تعديل على الروابط او المواد يرجى التواصل
                    Telegram / http://t.me/Laravelmah_bot




                    🔵 في حال رابط القروب ماطلع لك يعني الى الان ماوصلني

                    🔵 اذا عندك الرابط ارسله لي على التليقرام';

        $this->say($message);
        $this->askForCourses( );
    }

    private function askForCourses()
    {
        $courses = Course::pluck('title')->toArray();
        $keyboard = $this->createButtons($courses);
        $this->ask(self::Main_Menu, function (string $answer): void {
            $this->lastCourse = $answer;
            $this->askForMaterials($answer);
        }, $keyboard->toArray());
    }

    private function askForMaterials($course)
    {
        $materials = Material::whereHas('course', function($q) use($course) {
            $q->whereTitle($course);
        })->pluck('name')->toArray();

        $keyboard = $this->createButtons($materials, true);

        $this->ask($course, function (string $answer): void {

            if($this->clickedOnBackButton($answer) || $this->clickedOnMainMenuButton($answer)) {
                $this->askForCourses();
            } else {
                $this->lastMaterial = $answer;
                $this->askForSections($answer);
            }

        }, $keyboard->toArray());
    }

    private function askForSections($material)
    {
        $sections = Section::whereHas('material', function($q) use($material) {
                        $q->whereName($material);
                    })->pluck('name')->toArray();

        $keyboard = $this->createButtons($sections, true);

        $this->ask($material, function (string $answer, $sections): void {

            if( $this->clickedOnBackButton($answer)) {
                $this->askForMaterials($this->lastCourse);
            } else if( $this->clickedOnMainMenuButton($answer)) {
                $this->askForCourses();
            } else {
                $this->getWhatsAppLink($answer);
            }

        }, $keyboard->toArray());
    }

    private function getWhatsAppLink($section)
    {
        $whatsAppLink = Section::whereName($section)->first()->link_whatsup;

        $sections = Section::whereHas('material', function($q) {
            $q->whereName($this->lastMaterial);
        })->pluck('name')->toArray();
        $keyboard = $this->createButtons($sections, true);

        $this->ask($whatsAppLink, function (string $answer): void {

            if( $this->clickedOnBackButton($answer)) {
                $this->askForMaterials($this->lastCourse);
            } else if( $this->clickedOnMainMenuButton($answer)) {
                $this->askForCourses();
            } else {
                $this->getWhatsAppLink($answer);
            }

        }, $keyboard->toArray());
    }

    private function clickedOnBackButton($answer)
    {
        return $answer == self::BACK;
    }

    private function clickedOnMainMenuButton($answer)
    {
        return $answer == self::Main_Menu;
    }

    private function createButtons($buttonsName, $backAndMainButtons = false)
    {
        $keyboard = Keyboard::create()
            ->oneTimeKeyboard()
            ->type(Keyboard::TYPE_KEYBOARD)
            ->resizeKeyboard(true);


        foreach($buttonsName as $buttonName) {
            $keyboard->addRow(KeyboardButton::create($buttonName));
        }

        if($backAndMainButtons) {
            $keyboard->addRow(KeyboardButton::create($this->backAndMainButtons[0]), KeyboardButton::create($this->backAndMainButtons[1]));
        }

        return $keyboard;
    }
}
