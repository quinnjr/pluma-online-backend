<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Models;

use \Phalcon\Mvc\Model;
use \Phalcon\Mvc\Model\Validator\Email;

class Users extends Model {
  /**
   *
   * @var integer
   */
  public int $id;

  /**
   *
   * @var string
   */
  public string $email;

  /**
   *
   * @var string
   */
  public string $password_hash;

  /**
   *
   * @var DateTime
   */
  protected string $created_at;

  /**
   *
   * @var DateTime
   */
  protected string $updated_at;

  /**
   * Initialize the Users model.
   */
  public function initialize() {
    $this->setSchema('public');
    $this->setSource('users');
  }

  /**
   * Validation business logic.
   *
   * @return boolean
   */
  public function validation(): bool {
    $this->validate(
      new Email([
        'field' => 'email',
        'required' => 'true'
      ])
    );

    if ($this->validationHasFailed() == true) {
      return false;
    }

    return true;
  }
}
