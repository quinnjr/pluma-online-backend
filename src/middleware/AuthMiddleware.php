<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

use \Phalcon\Events\Event;
use \Phalcon\Mvc\Micro;
use \Phalcon\Mvc\Micro\MiddlewareInterface;
use \Firebase\JWT\ExpiredException;

/**
 *
 */
class AuthMiddleware implements MiddlewareInterface {

  /**
   * Before anything happens
   *
   * @param Event $event
   * @param Micro $application
   *
   * @returns bool
   */
  public function beforeHandleRoute(Event $event, Micro $application): bool {
    $headers = $this->request->getHeaders();

    if (!isset($headers['Authorization'] || empty($headers['Authorization']))) {
      $this->response->setStatusCode(403, 'Forbidden');
      $this->response->send();
      return false;
    }

    try {
      JWT::$leeway = 180;
      $user = JWT::decode($token, $this->getConfig()->salt, ['HS256']);
    } catch (ExpiredException $e) {
      $this->response->setStatusCode(405, $e->getMessage());
      $this->response->send();
      return false;
    }

    return true;
  }

  /**
   * Calls the middleware.
   *
   * @param Micro $application
   *
   * @returns bool
   */
  public function call(Micro $application): bool {
    return true;
  }
}
