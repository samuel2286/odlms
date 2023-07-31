<?php

function sendSMS($phone, $msg){

    //$endPoint = "https://api.mnotify.com/api/sms/quick";
    //$apiKey = "	3FpKe14Ot2elB5tznxevxAwkVobrNabQQAB4CFS5I6qjc";
    $url = $endPoint. '?key=' . $apiKey;
    $data = [
        // 'recipient' => explode($phone),
        // 'sender' => '3WASP',
        // 'message' => $message,
        // 'is_schedule' => 'false',
        // 'schedule_date' => ''
        //$to =  explode($phone);
        'recipient' => implode($phone),
        $key="3FpKe14Ot2elB5tznxevxAwkVobrNabQQAB4CFS5I6qjc";
        $message= $_POST['msg'];
        $sender_id="3WASP";
        $msg = urlencode($msg);
        $url="https://apps.mnotify.net/smsapi?key=$key&to=$to&msg=$msg&sender_id=$sender_id";
    ];

    $ch = curl_init();
    $headers = [];
    $headers[] = "Content-Type: application/json";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $result = curl_exec($ch);
    $result = json_decode($result,true);
    curl_close();
    return $result;
}

echo json_encode($_REQUEST);
?>