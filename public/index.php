<?php

declare(strict_types=1);

require_once '../src/Pushover.php';

$configJson = file_get_contents('../config.json');

if ($configJson === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to read configuration file']);
    exit;
}

$config = json_decode($configJson);

if ($config === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to parse configuration JSON']);
    exit;
}

$pushover = new Pushover($config);

header('Content-Type: application/json');

$usageToken = $_GET['usageToken'] ?? null;

if ($usageToken !== $config->usageToken) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' ||
    isset($_GET['preparedAction']) && isset($config->preparedActions) && array_key_exists($_GET['preparedAction'], (array) $config->preparedActions) ||
    isset($_GET['sendViaGet'])
) {
    $data = [];

    if (isset($_GET['preparedAction']) && isset($config->preparedActions)) {
        $data = (array) $config->preparedActions->{$_GET['preparedAction']};
    } elseif (isset($_GET['sendViaGet']) || $_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
        unset($data['usageToken']);
    }

    echo $pushover->sendNotification($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($pushover->getParameters());
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
