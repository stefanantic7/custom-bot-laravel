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
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . 'EAAWc81PlKzgBABXSnZCYZAFYsZA3CqBdT3isv0oUZCGQ9BMuZAc8cVMEtGRaZCUNUb1zV6r0uasKu8s1mpK5JiM8Ogxr2Vv3YvBthW8YX70Iwr4OZCivwOlUAH5enayHnbVZCCintx7GrgEXqOE5W9VVPaLLy7qtjM6i2gN3dDijuAZDZD');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);

    }
}
