<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function receive(Request $request)
    {
        $data = $request->all();
        dd($data);
        //get the user’s id
        $id = $data["entry"][0]["messaging"][0]["sender"]["id"];
        $this->sendTextMessage($id, $id = $data["entry"][0]["messaging"][0]["message"]);
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
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . 'EAAWc81PlKzgBALECtJ93fEPAnxPaNy3yrLxT9AP312ZAZAb1UrepZBpp4jNNbOKu97dOLoLQjqqvMvscrg9uLbZCTDumiTHrN1eRDREGfH1A5heX8mK85kuaGTvz9VI0IZAbO7AI5008bYQWXpZBVrfZBqMRbjbDXr0rVMOmZA6kngZDZD');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);

    }
}
