<?php

$url = 'https://healthself.ours.web.id/api/auth/login';
$data = ['email' => 'zidanbudek13@gmail.com', 'password' => '12345678'];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\nAccept: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
    ]
];
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    die("Login failed");
}

$response = json_decode($result, true);
$token = $response['data']['access_token'];

$dashboardUrl = 'https://healthself.ours.web.id/api/moods';
$options = [
    'http' => [
        'header'  => "Authorization: Bearer $token\r\nAccept: application/json\r\n",
        'method'  => 'GET'
    ]
];
$context  = stream_context_create($options);
$dashboardResult = file_get_contents($dashboardUrl, false, $context);

echo $dashboardResult;
