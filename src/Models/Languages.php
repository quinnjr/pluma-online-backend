<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Models;

use \Phalcon\Mvc\Model;

class Languages extends Model {

  /**
   *
   * @var integer
   * @Primary
   * @Identity
   * @Column(type="integer", length=32, nullable=false)
   */
  protected int $id;

  /**
   *
   * @var string
   * @Column(nullable=false)
   */
  protected string $name;

  /**
   * Initialize method for model.
   */
  public function initialize() {
    $this->setSchema('public');
    $this->setSource('languages');
  }
}
