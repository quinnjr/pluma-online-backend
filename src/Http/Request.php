<?php declare(strict_types=1);

// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Http;

use \Phalcon\Http\Request as PhalconRequest;
use function \str_replace;

class Request extends PhalconRequest {
  /**
   * @return string
   */
  public function getBearerToken(): string {
    return str_replace('Bearer ', '', $this->getHeader('Authorization'));
  }
}
