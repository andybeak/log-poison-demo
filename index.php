<?php

require('vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('poison');
$log->pushHandler(new StreamHandler(__DIR__ . '/log/application.log', Logger::DEBUG));

// log the details of the user visit
$visitDetails = [
    'ip' => $_SERVER['REMOTE_ADDR'],
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'agent' => $_SERVER['HTTP_USER_AGENT'],
    'referer' => $_SERVER['HTTP_REFERER'] ?? 'not set'
];
$log->info("Request", $visitDetails);

// get translation language
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en-US,en;q=0.5';
    $parts = explode(',', $acceptLang);
    $lang = substr($parts[1], 0, 2);
}

// include translation file
$translationFilename = __DIR__ . DIRECTORY_SEPARATOR . 'translate' . DIRECTORY_SEPARATOR . $lang;

$trans = require($translationFilename);

// display the page
require('template/page.php');