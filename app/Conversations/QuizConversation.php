<?php

namespace App\Conversations;

use App\Models\Question;
use App\Models\UserScore;
use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\Drivers\Telegram\TelegramDriver;

class QuizConversation extends Conversation
{
    /** @var Question */
    protected $quizQuestions;

/** @var integer */
    protected $userPoints = 0;

/** @var integer */
    protected $userCorrectAnswers = 0;

/** @var integer */

/** @var integer */
    protected $currentQuestion = 1;

    protected $questionCount = 0;



    public function run()
    {

        $this->quizQuestions = Question::all()->shuffle();
        $this->questionCount = $this->quizQuestions->count();
        $this->quizQuestions = $this->quizQuestions->keyBy('id');

        $this->showInfo();

    }
    private function showInfo()
    {

        $telegramUser = $this->bot->getUser();
        $telegramUserId = $telegramUser->getId();


        // $this->say('dd',$telegramUserId);
        // $this->say('Message', $telegramUserId,TelegramDriver::class);





        // $this->say('You will be shown ' . $this->questionCount . ' questions about Laravel. Every correct answer will reward you with a certain amount of points. Please keep it fair and don\'t use any help. All the best! 🍀');
        $this->checkForNextQuestion();
    }

    private function checkForNextQuestion()
    {

        if ($this->quizQuestions->count()) {
            return $this->askQuestion($this->quizQuestions->first());
        }

        $this->showResult();
    }
    private function askQuestion(Question $question)
    {
        $questionTemplate = BotManQuestion::create($question->question);

        $questionText = '➡️ Question: ' . $this->currentQuestion . ' / ' . $this->questionCount . ' : ' . $question->question;
        $questionTemplate = BotManQuestion::create($questionText);

        $this->ask($questionTemplate, function (Answer $answer) use ($question) {

            // $this->bot->driver(TelegramDriver::DRIVER_NAME)->endConversation();


            $quizAnswer = Question::where('answer', $answer->getText())->first();

            if (!$quizAnswer) {
                $answerResult = '❌';
            } else {
                $this->userPoints += 1;
                $this->userCorrectAnswers++;

                $answerResult = '✅';
            }
            $this->quizQuestions->forget($question->id);


            $this->currentQuestion++;

          $user= $this->saveUser($this->bot->getUser(), $this->userPoints, $this->userCorrectAnswers);

            $this->say("{$user->name}-Your answer:{$answer->getText()} {$answerResult}");



            return $this->checkForNextQuestion();

        });
    }

    private function showResult()
    {

        if ($this->userPoints >= ceil($this->questionCount /2) ) {
            $this->say("yor score: {$this->userPoints} you win 🏁");

        } else {
            $this->say("your  score:  {$this->userPoints}  you lose");

        }

        // $this->bot->driver(TelegramDriver::DRIVER_NAME)->endConversation();

        // $this->stopsConversation('your win');



    }

        public  function saveUser(UserInterface $botUser, int $userPoints, int $userCorrectAnswers)
    {
        $user = UserScore::updateOrCreate(['chat_id' => $botUser->getId()], [
            'chat_id' => $botUser->getId(),
            'name' => $botUser->getFirstName().' '.$botUser->getLastName(),
            'points' => $userPoints,
            'correct_answers' => $userCorrectAnswers,
        ]);

        $user->increment('tries');

        $user->save();

        return $user;
    }


}
