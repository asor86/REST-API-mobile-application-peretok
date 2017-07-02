<?php
/**
 * Created by PhpStorm.
 * Developer: Oreshkov Alexander
 * Email: asor86@ya.ru
 * Date: 22.07.2016
 */

$firebaseUrl = "https://fcm.googleapis.com/fcm/send";

$headers = array(
    'Authorization: key=AIzaSyCFTCPgp-NL6eK3N8A-zyyz31JXn1RGRFE',
    'Content-Type: application/json'
);

$data = '{  "notification": {
                "title": "title",
                "body": "text messages",
                "sound": "default",
                "icon" : "myicon"
              },
            "registration_ids" : ["d3xtPPnqC24:APA91bF9vYZ2gilMA1EVo44mDUy02XvcS9-5_QLviU_7kQo1IfMwac16g38hpTjkTLqkOevUkV4ggsdNFkP52jMw5H0GhpnAd2DsaarYK-_REpKXH8_k6Af4EyD4KWQPboOfakA63vBp"]
         }';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $firebaseUrl);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//if ($this->caInfoPath !== false) {
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
//    curl_setopt($ch, CURLOPT_CAINFO, $this->caInfoPath);
//} else {
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//}

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$resultBody = curl_exec($ch);
$resultHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

