<?php declare(strict_types=1);
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Middleware;

use \Phalcon\Events\Event;
use \Phalcon\Mvc\Micro;
use \Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * RequestMiddleware
 *
 * Check incoming payload
 */
class RequestMiddleware implements MiddlewareInterface {
  /**
   * Before the route is executed
   *
   * @param Event $event
   * @param Micro $application
   *
   * @returns bool
   */
  public function beforeExecuteRoute(Event $event, Micro $application): bool {
    $request = $application->request;

    if ($request->isPost()) {
      \json_decode($request->getRawBody());
      if (JSON_ERROR_NONE !== \json_last_error()) {
        $application->response->setStatusCode(400, 'Bad Request');
        $application->response->setJsonContent([
          'message' => 'POST body could not be decoded as valid JSON'
        ]);
        return false;
      }
    }
    return true;
  }

  /**
   * Calls the middleware
   *
   * @param Micro $application
   *
   * @returns bool
   */
  public function call(Micro $application): bool {
    return true;
  }
}
