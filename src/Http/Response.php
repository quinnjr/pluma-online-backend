<?php declare(strict_types=1);

// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Http;

use \Phalcon\Http\Response as PhalconResponse;
use \Phalcon\Http\ResponseInterface;
use \Phalcon\Messages\Messages;

class Response extends PhalconResponse {
  const OK = 200;
  const CREATED = 201;
  const ACCEPTED = 202;
  const BAD_REQUEST = 400;
  const UNAUTHORIZED = 401;
  const FORBIDDEN = 403;
  const NOT_FOUND = 404;
  const INTERNAL_SERVER_ERROR = 500;

  /**
   * Send the response back with E-Tag set.
   *
   * @return ResponseInterface
   */
  public function send(): ResponseInterface {
    $content = $this->getContent();
    $eTag = \sha1($content);

    $this->setHeader('E-Tag', $eTag);

    return parent::send();
  }

  /**
   * Sets the payload code as an error.
   *
   * @param string $detail
   *
   * @return $this
   */
  public function setPayloadError(string $detail = ''): self {
    $this->setJsonContent([
      'errors' => [$detail]
    ]);

    return $this;
  }

  /**
   *
   *
   * @param Messages $errors
   *
   * @return $this
   */
  public function setPayloadErrors(mixed $errors): self {
    $data = [];
    foreach ($errors as $error) {
      $data[] = $error->getMessage();
    }

    $this->setJsonContent([
      'errors' => $data
    ]);

    return $this;
  }

  /**
   *
   *
   * @param null|string|array $content
   *
   * @return $this
   */
  public function setPayload($content = []): self {
    $data = (\is_array($content)) ? $content : [ 'data' => $content ];
    $data = (isset($data['data'])) ? $data : [ 'data' => $data ];

    $this->setJsonContent($data);

    return $this;
  }
}
