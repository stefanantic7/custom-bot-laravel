<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function receive(Request $request)
    {
        $data = $request->all();
        if(isset($data['entry'])){
            //get the user’s id
            $id = $data["entry"][0]["messaging"][0]["sender"]["id"];
//        $data["entry"][0]["messaging"][0]["message"]["text"]
            $received = $data["entry"][0]["messaging"][0]["message"];
            if(isset($received)){
                $this->sendTextMessage($id, $received['text']);
            }
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
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . 'EAAWc81PlKzgBAP9wVFxF5DezzYPambh85Rz2JIoKuRRuj02rKyBkic5YH3UKSTsnzbgdDDtduy9aaT6gwlraEQFGSZCqau82GPJYmWwjtN6lGq27zsBTtaruC1c5AmXffOryZCnaPc0u6fEX6d0e7tuJy8Ih9OsTeDUnoJEQZDZD');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);

    }
}
