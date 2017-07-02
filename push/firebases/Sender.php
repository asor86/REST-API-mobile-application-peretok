<?php

/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 22.07.2016
 */
namespace pushApi;

class Sender
{

    private $serverKey = 'AIzaSyCFTCPgp-NL6eK3N8A-zyyz31JXn1RGRFE';
    private $firebaseUrl = "https://fcm.googleapis.com/fcm/send";

    public function __construct($serverKey = null, $firebaseUrl = null)
    {
        if ($serverKey) {
            $this->serverKey = $serverKey;
        }
        if ($firebaseUrl) {
            $this->firebaseUrl = $firebaseUrl;
        }
    }

    public function sendMessage($title, $body, $IDs = array())
    {
        if (!$this->serverKey) {
            return json_encode(
                array(
                    "operation" => "failure",
                    "msg" => "server key not found")
            );
        }

        $headers = array(
            'Authorization: key=' . $this->serverKey . '',
            'Content-Type: application/json'
        );

        if (!$IDs) {
            return json_encode(
                array(
                    "operation" => "failure",
                    "msg" => "token not found")
            );
        }

        $rewData = array(
            "notification" => array(
                "title" => $title,
                "body" => $body,
                "sound" => "default",
            ),
            "registration_ids" => $IDs
        );

        $data = json_encode($rewData);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->firebaseUrl);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $resultBody = curl_exec($ch);
        $resultHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $resultBody;
    }

}
