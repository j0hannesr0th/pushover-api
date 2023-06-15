<?php

declare(strict_types=1);

require_once '../src/Pushover.php';

$configJson = file_get_contents('../config.json');

if ($configJson === false) {
    // Error handling when the file cannot be read
    http_response_code(500);
    echo json_encode(['error' => 'Failed to read configuration file']);
    exit;
}

$config = json_decode($configJson);

if ($config === null) {
    // Error handling when the JSON cannot be decoded
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' || (isset($_GET['action']) && $_GET['action'] === 'send')) {
    $data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
    unset($data['usageToken'], $data['action']);
    echo $pushover->sendNotification($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($pushover->getParameters());
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
