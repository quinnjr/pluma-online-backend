<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

declare(strict_types=1);
setlocale(LC_ALL, 'en_US.utf-8');

defined('APP_PATH') || define('APP_PATH', realpath(__DIR__));
defined('VENDOR_PATH') || define('VENDOR_PATH', realpath(__DIR__ . '/../vendor'));

use \Dotenv\Dotenv;
use \Firebase\JWT;
use \Phalcon\Config;
use \Phalcon\Db\Adapter\Pdo\Postgresql;
use \Phalcon\Di\FactoryDefault;
use \Phalcon\Events\Event;
use \Phalcon\Events\Manager;
use \Phalcon\Http\Response;
use \Phalcon\Loader;
use \Phalcon\Mvc\Micro;
use \Phalcon\Mvc\Micro\Exception;

try {
  $loader = new Loader();
  $loader->registerNamespaces([
    'PluMA\Controllers' => APP_PATH . '/controllers/',
    'PluMA\Models' => APP_PATH . '/models/',
    'Dotenv' => VENDOR_PATH . '/vlucas/phpdotenv/src',
    'PhpOption' => VENDOR_PATH . '/phpoption/phpoption/src/PhpOption',
    'Firebase\JWT' => VENDOR_PATH . '/firebase/php-jwt/src'
  ]);
  $loader->register();

  $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
  $dotenv->load();

  $di = new FactoryDefault();

  $di->setShared('config', function() {
    return new Config([
      'database' => [
        'host' => $_ENV['DATABASE_HOST'],
        'username' => $_ENV['DATABASE_USERNAME'],
        'password' => $_ENV['DATABASE_PASSWORD'],
        'dbname' => $_ENV['DATABASE_DBNAME']
      ],
      'application' => [
        'appDir' => __DIR__,
        'modelsDir' => __DIR__ . '/models/',
        'migrationsDir' => __DIR__ . '/migrations/',
        'salt' => $_ENV['APP_SALT']
      ]
    ]);
  });

  $di->setShared('response', function() {
    $response = new Response();
    $response->setContentType('application/json', 'utf-8');
    $response->setHeader('Access-Control-Allow-Origin', '*');
    return $response;
  });

  $di->set('db', function() {
    $config = $this->get('config');
    return new Postgresql([
      'host' => $config->database->host,
      'username' => $config->database->username,
      'password' => $config->database->password,
      'dbname' => $config->database->dbname
    ]);
  });

  $manager = new Manager();

  $manager->attach('micro:afterExecuteRoute', function (
    Event $event,
    Micro $application
  ) {
    $res = $application->getReturnedValue();

    if (is_null($res)) {
      $application->response->setStatusCode(204, 'No Content');
    } elseif (!is_null($res)) {
      $application->response->setStatusCode(200, 'Success')
        ->setJsonContent(['data' => $res]);
    } else {
      throw new Exception('Bad Response', 500);
    }

    $application->response->setHeader('Access-Control-Allow-Origin', '*');

    $application->response
      ->sendHeaders()
      ->send();
    return true;
  });

  $app = new Micro($di);

  $app->setEventsManager($manager);

  $app->notFound(function() use ($app) {
    $app->response
      ->setStatusCode(404, 'Not Found')
      ->setJsonContent([
        'error' => 'Not Found'
      ])
      ->send();
  });

  require_once __DIR__ . '/routes.php';

  $app->handle($_SERVER["REQUEST_URI"]);
} catch (Exception $e) {
  $app->response->setStatusCode(500, 'Internal Server Error')
    ->setJsonContent([
      'code' => $e->getCode(),
      'message' => $e->getMessage()
    ])
    ->send();
} catch (\Exception $e) {
  $app->response->setStatusCode(500, 'Internal Server Error')
    ->setJsonContent([
      'code' => 500,
      'message' => $e->getMessage()
    ])
    ->send();
}
