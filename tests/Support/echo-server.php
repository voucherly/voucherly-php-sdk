<?php

$uri = $_SERVER['REQUEST_URI'];
if (0 === strpos($uri, '/sleep')) {
    usleep(1500000);
}
if (0 === strpos($uri, '/status/')) {
    http_response_code((int) substr($uri, 8));
}

$headers = [];
foreach ($_SERVER as $name => $value) {
    if (0 === strpos($name, 'HTTP_')) {
        $headers[strtolower(str_replace('_', '-', substr($name, 5)))] = $value;
    }
}
foreach (['CONTENT_TYPE' => 'content-type', 'CONTENT_LENGTH' => 'content-length'] as $name => $header) {
    if (isset($_SERVER[$name])) {
        $headers[$header] = $_SERVER[$name];
    }
}

header('Content-Type: application/json');
header('X-Echo: yes');
echo json_encode([
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $uri,
    'headers' => $headers,
    'body' => file_get_contents('php://input'),
]);
