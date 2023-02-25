<?php

namespace App\Conversations;

use App\Models\Material;
use App\Models\Section;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class SubjectConversation extends Conversation
{
    private $backAndMainButtons = ['Back', 'Main Menu'];
    private $lastSection;

    public function run()
    {
        $this->askForMaterials( );
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

    private function askForMaterials()
    {
        $materials = Material::pluck('name')->toArray();
        $keyboard = $this->createButtons($materials);
        $this->ask('Choose a material', function (string $answer): void {

            $this->askForSections($answer);

        }, $keyboard->toArray());
    }

    private function askForSections($material)
    {
        $sections = Section::whereHas('material', function($q) use($material) {
                        $q->whereName($material);
                    })->pluck('name')->toArray();

        $keyboard = $this->createButtons($sections, true);

        $this->ask('Choose a section', function (string $answer): void {

            if( in_array($answer, $this->backAndMainButtons)) {
                $this->askForMaterials();
            } else {
                $this->getWhatsAppLink($answer);
                $this->lastSection = $answer;
            }

        }, $keyboard->toArray());
    }

    private function getWhatsAppLink($section)
    {
        $whatsAppLink = Section::whereName($section)->first()->link_whatsup;
        if( in_array($section, $this->backAndMainButtons)) {
            $this->askForMaterials();
        } else {
            $this->say($whatsAppLink);
            $this->askForSections($this->lastSection);
        }
    }
}
