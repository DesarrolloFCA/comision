<?php
header('Content-Type: text/plain');

$keys = [
    'HTTPS',
    'HTTP_X_FORWARDED_PROTO',
    'HTTP_X_FORWARDED_HOST',
    'HTTP_X_FORWARDED_PORT',
    'REQUEST_SCHEME',
    'SERVER_PORT',
    'HTTP_HOST',
    'REQUEST_URI',
    'REMOTE_ADDR',
];

foreach ($keys as $key) {
    echo $key . '=' . ($_SERVER[$key] ?? '(null)') . PHP_EOL;
}
