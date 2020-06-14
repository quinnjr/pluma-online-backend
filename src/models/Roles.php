<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Models;

use \Phalcon\Mvc\Model;

class Roles extends Model {

  /**
   *
   * @var integer
   */
  protected integer $role_id;

  /**
   *
   * @var string
   */
  protected string $role_name;

  public function initialize() {
    $this->setSchema('public');
    $this->setSource('roles');
  }
}
