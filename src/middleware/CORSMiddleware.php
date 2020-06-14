<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * CORSMiddleware
 *
 * CORS checking
 */
class CORSMiddleware implements MiddlewareInterface {
  /**
   * Before anything happens
   *
   * @param Event $event
   * @param Micro $application
   *
   * @returns bool
   */
  public function beforeHandleRoute(Event $event, Micro $application): bool {
    if ($application->request->getHeader('ORIGIN')) {
      $origin = $application->request->getHeader('ORIGIN');
    } else {
      $origin = '*';
    }

    $application->response
      ->setHeader('Access-Control-Allow-Origin', $origin)
      ->setHeader('Access-Control-Allow-Methods','GET,PUT,POST,DELETE,OPTIONS')
      ->setHeader(
        'Access-Control-Allow-Headers',
        'Origin, X-Requested-With, Content-Range, ' .
        'Content-Disposition, Content-Type, Authorization'
      )
      ->setHeader('Access-Control-Allow-Credentials', 'true');
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
