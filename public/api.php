<?php

require __DIR__ .'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/../');
$dotenv->load();

$app = new \Sentral\Challenge\App();
$app::init(1);
