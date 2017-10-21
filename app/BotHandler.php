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
        if(strtolower($message->getMessage()) == 'start') {
            FaceUser::where('face_id', $message->getSender())->delete();
            $this->newUser($message->getSender());
        }
        else if (strtolower($message->getMessage()) == 'restart') {
            $this->deleteUser($message->getSender());
        }
        else {
            $user = FaceUser::where('face_id', $message->getSender())->first();
            if($user){
                if(strtolower($message->getMessage()) == 'da' || strtolower($message->getMessage()) == 'ne'){
                    $this->handleAnswer($message->getSender(), strtolower($message->getMessage()));
                }
                else {
                    $this->send(new Text($message->getSender(), 'Odgovarajte sa "da" ili "ne"'));
                }
            }
            else {
                $this->send(new Text($message->getSender(), 'Za pocetak, unesite: "start"'));
            }
        }
    }

    private function newUser($faceId)
    {
        $this->send(new Text($faceId, 'Nova sesija otvorena. Odgovarajte sa "da" ili "ne". Ukoliko zelite novo pokretanje, unesite: "restart"'));

        $user = new FaceUser();
        $user->face_id = $faceId;

        $rule = Rule::with('conditions')->first();

        $user->question = $rule->conditions[0]->text;
        $user->save();

        $this->send(new Text($faceId, $user->question));
    }

    private function handleAnswer($faceId, $answer){
        $rules = Rule::all();
        $user = FaceUser::where('face_id', $faceId)->first();
        foreach ($rules as $rule) {
            $returned = $rule->check($user, $answer);
            if(is_null($returned)) {
                $this->send(new Text($faceId, $user->question));
                return;
            }

            if($returned === true ){
                $this->send(new Text($faceId, 'Odgovor za Vas: '.$rule->conclusion->text));
                $user->delete();
                $this->send(new Text($faceId, 'Da biste krenuli ponovo, ukucajte: "start"'));

                return;
            }
        }
        $this->send(new Text($faceId, "Nema rezultata"));
    }

    private function  deleteUser($faceId){
        FaceUser::where('face_id', $faceId)->delete();
        $this->send(new Text($faceId, "Vasa sesija je obrisana."));
        $this->newUser($faceId);
    }
}
