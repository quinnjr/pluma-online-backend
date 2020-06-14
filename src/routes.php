<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

use \Phalcon\Mvc\Micro\Collection;

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
$pluginsCollection->post('/create', 'createAction');
$pluginsCollection->put('/{id:[0-9]+}', 'updateAction');
$pluginsCollection->delete('/{id:[0-9]+}', 'deleteAction');

$app->mount($pluginsCollection);

// TODO: Admin/Authorization routes
