<?php declare(strict_types=1);
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA;

setlocale(LC_ALL, 'en_US.utf-8');

use \Dotenv\Dotenv;
use \Phalcon\Cache;
use \Phalcon\Cache\AdapterFactory;
use \Phalcon\Cache\Adapter\Apcu;
use \Phalcon\Config;
use \Phalcon\Db\Adapter\Pdo\Postgresql;
use \Phalcon\Events\Manager as EventsManager;
use \Phalcon\Http\Response;
use \Phalcon\Http\Request;
use \Phalcon\Loader;
use \Phalcon\Mvc\Micro;
use \Phalcon\Mvc\Micro\Collection;
use \Phalcon\Mvc\Model\Manager as ModelsManager;
use \Phalcon\Mvc\Model\MetaData\Apcu as MetadataManager;
use \Phalcon\Storage\SerializerFactory;

use PluMA\Di\FactoryDefault;
use PluMA\Exception;
use PluMA\Jwt;
use PluMA\Middleware\CORSMiddleware;
use PluMA\Middleware\RefreshToken;
use PluMA\Middleware\RequestMiddleware;
use PluMA\Middleware\ResponseMiddleware;

class Bootstrap
{
  public function run(string $uri)
  {
    try {

      $loader = new Loader();

      $loader->registerNamespaces([
        'PluMA\\' => __DIR__,
        'PluMA\\Controllers' => __DIR__ . '/Controllers/',
        'PluMA\\Models' => __DIR__ . '/Models/',
      ]);

      $loader->register();

      $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
      $dotenv->load();

      $di = new FactoryDefault();

      $di->set('cache', function() {
        $serializerFactory = new SerializerFactory();

        $options = [
          'defaultSerializer' => 'Json',
          'lifetime' => 7200,
          'prefix' => 'biorg-'
        ];

        $adapter = new Apcu($serializerFactory, $options);

        return new Cache($adapter);
      }, true);

      $di->set('config', function() {
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
            'migrationsDir' => __DIR__ . '/../migrations/'
          ],
          'jwtSalt' => $_ENV['JWT_SALT']
        ]);
      }, true);

      $di->set('db', function() {
        $config = $this->get('config');
        return new Postgresql([
          'host' => $config->database->host,
          'username' => $config->database->username,
          'password' => $config->database->password,
          'dbname' => $config->database->dbname
        ]);
      });

      $di->set('dispatcher', function() {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('PluMA\Controllers');
        return $dispatcher;
      }, true);

      // $di->set('modelsManager', function() {
      //   return new ModelsManager();
      // }, true);

      $di->set('modelsMetadata', function() {
        $serializerFactory = new SerializerFactory();
        $factory = new AdapterFactory($serializerFactory);
        $interface = $factory->newInstance('apcu');
        return new MetadataManager($factory, [
          'prefix' => 'biorg',
          'lifetime' => 86400
        ]);
      }, true);

      // $di->set('request', function() {
      //   $request = new Request();
      //   return $request;
      // }, true);

      $di->set('response', function() {
        $response = new Response();
        $response->setStatusCode(200, 'Success');
        $response->setContentType('application/json', 'utf-8');
        return $response;
      }, true);

      // $di->set('router', function() {
      //   $router = new \Phalcon\Mvc\Router();
      //   return $router;
      // }, true);

      $eventsManager = new EventsManager();
      $di->setEventsManager($eventsManager);

      $app = new Micro($di);

      $eventsManager->attach('micro:beforeExecuteRoute', new RequestMiddleware());
      $app->before(new RequestMiddleware());

      $eventsManager->attach('micro:beforeExecuteRoute', new CORSMiddleware());
      $app->before(new CORSMiddleware());

      $eventsManager->attach('micro:afterExecuteRoute', new ResponseMiddleware());
      $app->after(new ResponseMiddleware());

      $app->setEventsManager($eventsManager);

      $app->notFound(function() use ($app) {
        $app->response
          ->setStatusCode(404, 'Not Found')
          ->setJsonContent([
            'error' => 'Not Found'
          ]);
      });

      $app->get('/', function() use ($app) {
        return 'Here be dragons';
      });

      $categoriesCollection = new Collection();
      $categoriesCollection->setHandler('PluMA\Controllers\CategoriesController', true);
      $categoriesCollection->setPrefix('/categories');
      $categoriesCollection->get('/', 'getAction');

      $app->mount($categoriesCollection);

      $peopleCollection = new Collection();
      $peopleCollection->setHandler('PluMA\Controllers\PeopleController', true);
      $peopleCollection->setPrefix('/people');
      $peopleCollection->get('/', 'getAction');
      $peopleCollection->post('/create', 'createAction');
      $peopleCollection->put('/{id:[0-9]+}', 'updateAction');
      $peopleCollection->delete('/{id:[0-9]+}', 'deleteAction');

      $app->mount($peopleCollection);

      $pluginsCollection = new Collection();
      $pluginsCollection->setHandler('PluMA\Controllers\PluginsController', true);
      $pluginsCollection->setPrefix('/plugins');
      $pluginsCollection->get('/', 'getAction');
      $pluginsCollection->get('/update', 'checkUpdateAction');
      $pluginsCollection->post('/create', 'createAction');
      $pluginsCollection->put('/{id:[0-9]+}', 'updateAction');
      $pluginsCollection->delete('/{id:[0-9]+}', 'deleteAction');

      $app->mount($pluginsCollection);

      $authCollection = new Collection();
      $authCollection->setHandler('PluMA\Controllers\AuthController', true);
      $authCollection->setPrefix('/auth');
      $authCollection->post('/', 'loginAction');
      $authCollection->get('/refresh-token', 'refreshTokenAction');
      $authCollection->post('/register', 'registerAction');

      $app->mount($authCollection);

      $app->handle($uri);
    } catch (\Phalcon\Mvc\Micro\Exception $e) {
      $app->response->setStatusCode(500, 'Internal Server Error');
      $app->response->setJsonContent($e->getMessage());
    } catch (\Exception $e) {
      $app->response->setStatusCode(500, 'Internal Server Error');
      $app->response->setJsonContent($e->getMessage());
    } finally {
      if (!$app->response->isSent()) {
        $app->response->sendHeaders()
          ->send();
      }
    }
  }
}
