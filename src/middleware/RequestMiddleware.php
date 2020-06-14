<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

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
    json_decode($application->request->getRawBody());
    if (JSON_ERROR_NONE !== json_last_error()) {
      $application->response->redirect('/malformed');
      $application->response->send();

      return false;
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
