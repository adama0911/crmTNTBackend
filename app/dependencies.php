<?php

// DIC configuration

$container = $app->getContainer();

// --------------------------------------------------------------
// Service providers
// --------------------------------------------------------------

// PDO
$container['db'] = function ($c) {
    $db = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->exec("set names utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// monolog
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler(DIR."/tmp/logs/".date('Y-m').".log");
    $logger->pushHandler($file_handler);
    return $logger;
};
