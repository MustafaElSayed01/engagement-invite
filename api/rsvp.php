<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$guests = (int)($_POST['guests'] ?? 1);
$attendance = $_POST['attendance'] ?? '';
$message = trim($_POST['message'] ?? '');

if ($name === '' || mb_strlen($name) > 100 || !in_array($attendance, ['yes', 'no'], true) || $guests < 1 || $guests > 10 || mb_strlen($message) > 500) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$record = [
    'name' => $name,
    'guests' => $guests,
    'attendance' => $attendance,
    'message' => $message,
    'submitted_at' => date('c')
];

$file = __DIR__ . '/../responses.jsonl';
$ok = file_put_contents($file, json_encode($record, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);

if ($ok === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Could not save response']);
    exit;
}

echo json_encode(['success' => true]);
