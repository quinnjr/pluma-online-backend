<?php declare(strict_types=1);

// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Di;

use \Phalcon\Di as DependencyInjector;
use \Phalcon\Di\Service;

/**
 * Overwrite of the default FactoryDefault injector.
 */
class FactoryDefault extends DependencyInjector {

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();

    $this->services = [
      "dispatcher" => new Service("Phalcon\\Mvc\\Dispatcher", true),
      "eventsManager" => new Service("Phalcon\\Events\\Manager", true),
      "modelsManager" => new Service("Phalcon\\Mvc\\Model\\Manager", true),
      "modelsMetadata" => new Service("Phalcon\\Mvc\\Model\\MetaData\\Memory", true),
      "request" => new Service("PluMA\\Http\\Request", true),
      "response" => new Service("PluMA\\Http\\Response", true),
      "router" => new Service("Phalcon\\Mvc\\Router", true),
      "transactionManager" => new Service("Phalcon\\Mvc\\Model\\Transaction\\Manager", true)
    ];
  }
}
