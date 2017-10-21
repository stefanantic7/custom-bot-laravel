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
        $this->send(new Text($message->getSender(), "Default Handler: {$message->getMessage()}"));
    }

    private function newUser($faceId)
    {
        $user = new FaceUser();
        $user->face_id = $faceId;
        $user->save();

        $rule = Rule::with('conditions')->first();

        $this->send(new Text($faceId, $rule->conditions[0]));
    }

    private function  deleteUser($faceId){
        FaceUser::where('face_id', $faceId)->delete();
    }
}
