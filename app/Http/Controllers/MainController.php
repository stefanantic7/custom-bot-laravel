<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function receive(Request $request)
    {
        $data = $request->all();
        //get the user’s id
        $id = $data["entry"][0]["messaging"][0]["sender"]["id"];
//        $data["entry"][0]["messaging"][0]["message"]["text"]
        $received = $data["entry"][0]["messaging"][0]["message"]["text"];
        if(is_string($received)){
            $this->sendTextMessage($id, $received);

        }
    }

    private function sendTextMessage($recipientId, $messageText)
    {
        $messageData = [
            "recipient" => [
                "id" => $recipientId
            ],
            "message" => [
                "text" => $messageText
            ]
        ];
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . 'EAAWc81PlKzgBANgu2jib02nbhtYMQFWZBcAxknNllRhLuj35ZCjucveKRIhLetCRCqbECwoB8c1ts30js7F6CZCumR1rkXpWdb7v2DTIvscZANX3pJpq0v5ZA631fuvBp1iN3vhUyGlC2bW7G0wRcOMq34Tgh8KcGS0jwq6e9LwZDZD');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);

    }
}
