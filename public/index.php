<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new PluMA\Bootstrap();
$app->run($_SERVER["REQUEST_URI"]);
