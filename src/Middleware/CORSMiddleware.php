<?php declare(strict_types=1);
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Middleware;

use \Phalcon\Events\Event;
use \Phalcon\Mvc\Micro;
use \Phalcon\Mvc\Micro\MiddlewareInterface;

class CORSMiddleware implements MiddlewareInterface {
  /**
   *
   * @param Event $event
   * @param Micro $application
   *
   * @returns bool
   */
   public function beforeHandleRoute(Event $event, Micro $application) {
    if ($application->request->getHeader('Origin')) {
      $origin = $application->request->getHeader('Origin');
    } else {
      $origin = '*';
    }

    $application->response->setHeader('Access-Control-Allow-Origin', $origin);
    $application->response->setHeader(
      'Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS'
    );
    $application->response->setHeader(
      'Access-Control-Allow-Headers',
      'Origin, X-Requested-With, Content-Range, Content-Disposition,' .
      'Content-Type, Authorization'
    );
    $application->response->setHeader('Access-Control-Allow-Credentials', 'true');

    return true;
   }

   /**
    *
    * @param Micro $application
    *
    * @returns bool
    */
  public function call(Micro $application) {
    return true;
  }
}
