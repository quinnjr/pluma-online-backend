<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Controllers;

use \is_null;
use \Firebase\JWT;
use \Firebase\JWT\ExpiredException;
use \Phalcon\DI\Injectable;

/**
 * Class AbstractController
 *
 * @property \Phalcon\Cache $cache
 * @property \Phalcon\Config $config
 * @property \Phalcon\Db\Adapter\Pdo\Postgresql $db
 * @property \Phalcon\Http\Request $request
 * @property \Phalcon\Http\Response $response
 * @property \Phalcon\Mvc\Model\Manager $modelsManager
 */
abstract class AbstractController extends Injectable {

  /**
   * Route not found. HTTP 404 Error
   */
  const ERROR_NOT_FOUND = 404;

  /**
   * Invalid Request. HTTP 400 Error.
   */
  const ERROR_INVALID_REQUEST = 400;

  /**
   * Route is attempting to be accessed without authorization.
   */
  const ERROR_UNAUTHORIZED = 401;

  /**
   *
   */
  public function checkAuthorization(): bool {
    try {
      $token = $this->request->getHeader('Authorization')[0];

      if (is_null($token)) {
        throw new \Exception('No Authorization header was provided');
      }

      JWT::$leeway = 180;
      $user = JWT::decode($token, $this->config->jwtSalt, ['HS256']);
    } catch (ExpiredException $e) {
      $reauthorize = $this->request->getHeader('X-Refresh-Token')[0];

      if(is_null($token)) {
        throw new \Excpetion('No refresh token header was provided');
      }

      JWT::$leeway = 180;
      $refresh = JWT::decode($token, $this->config->jwtSalt, ['HS256']);

    } catch (\Exception $e) {
      $this->response->setStatusCode(405, $e->getMessage());
      $this->response->sendHeaders()
        ->send();
      return false;
    }

    return true;
  }
}
