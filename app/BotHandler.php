<?php

namespace App;

use Casperlaitw\LaravelFbMessenger\Contracts\BaseHandler;
use Casperlaitw\LaravelFbMessenger\Messages\ReceiveMessage;
use Casperlaitw\LaravelFbMessenger\Messages\Text;

/**
 * Class DefaultHandler
 * @package Casperlaitw\LaravelFbMessenger\Contracts
 */
class BotHandler extends BaseHandler
{
    /**
     * Handle the chatbot message
     *
     * @param ReceiveMessage $message
     *
     * @return mixed
     */
    public function handle(ReceiveMessage $message)
    {
        if($message->getMessage() == 'start') {
            $this->newUser($message->getSender());
        }
        else if ($message->getMessage() == 'restart') {
            $this->deleteUser($message->getSender());
        }
        else {
            $this->handleAnswer();
        }
    }

    private function newUser($faceId)
    {
        $user = new FaceUser();
        $user->face_id = $faceId;

        $rule = Rule::with('conditions')->first();

        $user->question = $rule->conditions[0]->text;
        $user->save();

        $this->send(new Text($faceId, $user->question));
    }

    private function handleAnswer(){

    }

    private function  deleteUser($faceId){
        FaceUser::where('face_id', $faceId)->delete();
        $this->send(new Text($faceId, "Vasa sesija je obrisana"));
    }
}
