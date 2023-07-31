<?php 


function sendSMS($phone, $message){

    $endPoint = "https://api.mnotify.com/api/sms/quick";
    $apiKey = "8IC7z6nCHw7m5x6ME2LnYXPYCe5Id9DlSfsrmQ43nbqsJ";
    $url = $endPoint. '?key=' . $apiKey;
    $data = [
        'recipient' => explode($phone),
        'sender' => 'mNotify',
        'message' => $message,
        'is_schedule' => 'false',
        'schedule_date' => ''
    ];
    ch = curl_init();
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
?>