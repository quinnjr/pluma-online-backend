<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Controllers;

use \Carbon\Carbon;
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Phalcon\Logger;

use PluMA\Models\Users;

class AuthController extends AbstractController {

  /**
   *
   */
  public function loginAction() {
    $body = $this->request->getJsonRawBody();

    if (\is_null($body->email) || !$body->email) {
      return $this->response->setStatusCode(400, 'Bad Request')
        ->setJsonContent([
          'message' => 'Email cannot be empty'
        ]);
    }

    if (\is_null($body->password) || !$body->password) {
      return $this->response->setStatusCode(400, 'Bad Request')
        ->setJsonContent([
          'message' => 'Password cannot be empty'
        ]);
    }

    // Find the requested user in the database.
    $user = Users::findFirst([
      'columns' => 'email, password_hash',
      'conditions' => 'email = :email:',
      'bind' => [
        'email' => $body->email
      ]
    ]);

    if (\is_null($user)) {
      return $this->response->setStatusCode(401, 'Unauthorized')
        ->setJsonContent([
          'message' => 'Email address ' . $email . ' is not a valid registered email address'
        ]);
    }

    // Check for a valid log in.
    if (!\password_verify($body->password, $user->password_hash)) {
      return $this->response->setStatusCode(401, 'Unauthorized')
        ->setJsonContent([
         'message' => 'Incorrect password provided'
        ]);
    } else {
      $now = Carbon::now();
      $expiry = Carbon::now()->addHours(2);

      $token_id = \base64_encode(\random_bytes(16));

      $jwt_payload = [
        'iss' => $_SERVER['SERVER_NAME'],
        'aud' => $_SERVER['SERVER_NAME'],
        'jti' => $token_id,
        'iat' => $now->timestamp,
        'nbf' => $now->timestamp + 1,
        'exp' => $expiry->timestamp
      ];

      return JWT::encode($jwt_payload, $this->config->jwt_salt);
    }
  }

  /**
   *
   */
  public function registerAction() {
    $body = $this->request->getJsonRawBody();

    if (\is_null($body)) {
      die();
    }

    return \password_hash($body->password, PASSWORD_ARGON2I);
  }

  /**
   *
   */
  public function refreshTokenAction() {

  }

  /**
   *
   */
  public function isAuthenticatedAction() {

  }
}
