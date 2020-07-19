<?php declare(strict_types=1);
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Middleware;

use \Phalcon\Http\Response;
use \Phalcon\Mvc\Micro;
use \Phalcon\Mvc\Micro\MiddlewareInterface;
use function \is_array;
use function \is_scalar;

/**
 *
 */
class ResponseMiddleware implements MiddlewareInterface {
  /**
   *
   *
   * @param Micro $application
   *
   * @return bool
   */
  public function call(Micro $application): void {
    $content = $application->getReturnedValue();

    if ($content instanceof Response) {
      return;
    } elseif (is_array($content) || is_scalar($content)) {
      $application->response->setJsonContent(['data' => $content]);
    } elseif (!isset($content)) {
      $application->response->setStatus(204, 'No Content');
    } else {
      throw new \Exception('Bad Response');
    }
  }
}
