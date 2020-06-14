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
  protected integer $user_id;

  /**
   *
   * @var string
   */
  protected string $user_email;

  /**
   *
   * @var string
   */
  protected string $user_password_hash;

  /**
   *
   * @var DateTime
   */
  protected DateTime $created_at;

  /**
   *
   * @var DateTime
   */
  protected DateTime $updated_at;

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
