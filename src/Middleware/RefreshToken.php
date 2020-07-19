<?php declare(strict_types=1);
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Middleware;

use function \is_null;
use \Phalcon\Events\Event;
use \Phalcon\Mvc\Micro;
use \Phalcon\Mvc\Micro\MiddlewareInterface;

class RefreshToken implements MiddlewareInterface {
  /**
   *
   * @param Event $event
   * @param Micro $application
   *
   * @returns bool
   */
  public function beforeHandleRoute(Event $event, Micro $application) {
    $bearerHeader = $application->request->getHeader('Authorization');

    if (is_null($bearerHeader)) {
      return true;
    }


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
