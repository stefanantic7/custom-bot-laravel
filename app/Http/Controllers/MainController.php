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

        $this->sendTextMessage($id, $data["entry"][0]["messaging"][0]["message"]["text"]);
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
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . 'EAAWc81PlKzgBAEWM79Wxcz3Ro47UZBZCWsFVCAvW4sCDEAZAv7yB42cyw8ruO8n4l7cpkqWyIPZCKorXZAxisdwvuDlTCY58ep7ZAfADpeXV1TJNAq7lzdiLbZCghBir8JnS9PnJhqRQlksscGyvWlx4iqsm0P2KdhlRp5jxX6SyAZDZD');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);

    }
}
