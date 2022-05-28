<?php

require_once './src/LandingPdo.php';
require_once './src/Validator.php';

function sendJson(mixed $data): void
{
    http_response_code(200);
    header('content-type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function sendError(string $error): void
{
    sendJson(['success' => false, 'error' => $error]);
}

$data = array_map(function ($field) {
    return htmlspecialchars($field);
}, $_POST);

if (!Validator::isUserValid($data['fio'], $data['email'], $data['phone'])) {
    sendError('Проверьте корректность ввода данных');
}

try {
    $landingPdo = new LandingPdo();
    $lastSendTime = $landingPdo->getLastSendTimeByUserEmail($data['email']);

    $now = time();
    $lastSendTime = $lastSendTime->getTimestamp();
    if ($lastSendTime) {
        $interval = $now - $lastSendTime;
        if ($interval < 3600) {
            sendError('Заявку повторно можно отправить только через ' . intdiv(3600 - $interval, 60) . ' минут');
        }
    }

    $fields = explode(' ', $data['fio']);
    $lastName = $fields[0];
    $firstName = $fields[1];
    $middleName = $fields[2] ?? '';
    $userId = $landingPdo->saveNewUser($lastName, $firstName, $middleName, $data['email'], $data['phone']);
    $landingPdo->saveComment($userId, $data['comment'], new DateTime());

    $message = 'Фамилия: ' . $lastName . "\r\n" .
        'Имя: ' . $firstName . "\r\n" .
        'Отчество: ' . $middleName . "\r\n" .
        'email: ' . $data['email'] . "\r\n" .
        'Телефон: ' . $data['phone'] . "\r\n" .
        'Комментарий: '  . $data['comment'] ;

    mail('holinvova@gmail.com',
        'Комментарий пользователя ' . $lastName . ' ' . $firstName,
        $message,
        "From: " . $data['email']);

    sendJson(['success' => true]);

} catch (Error $e) {
    sendError('Что-то пошло не так :(');
}

