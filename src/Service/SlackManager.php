<?php

namespace App\Service;

class SlackManager
{
    public function sendMessage($text)
    {
        $ch = curl_init("https://slack.com/api/chat.postMessage");

        $data = http_build_query([
            "token" => "xoxp-450380340246-449203244949-502978136114-f3de704586052c6595cd89796ac243c9",
            "channel" => 'log',
            "text" => $text,
            "username" => "Bot",
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}