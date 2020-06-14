<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Controllers;

use \Phalcon\DI\Injectable;

/**
 * Class AbstractController
 *
 * @property \Phalcon\Http\Request $request
 * @property \Phalcon\Http\Response $htmlResponse
 * @property \Phalcon\Db\Adapter\Pdo\Postgresql $db
 * @property \Phalcon\Config $config
 */
abstract class AbstractController extends Injectable {

  /**
   * Route not found. HTTP 404 Error
   */
  const ERROR_NOT_FOUND = 1;

  /**
   * Invalid Request. HTTP 400 Error.
   */
  const ERROR_INVALID_REQUEST = 2;
}
