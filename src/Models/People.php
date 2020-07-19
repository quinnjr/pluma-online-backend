<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Models;

use \Phalcon\Mvc\Model;

class People extends Model {

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
   * Column(nullable=false)
   */
  protected string $name;

  /**
   *
   * @var integer
   * @Column(type="integer", length=32, nullable=false)
   */
  protected int $role_id;

  /**
   *
   * @var string
   * @Column(type="string", nullable=false)
   */
  protected string $title;

  /**
   *
   * @var string
   * @Column(type="string", nullable=false)
   */
  protected string $speciality;

  /**
   *
   * @var string
   * @Column(type="string", nullable=true)
   */
  protected string $profile_picture;

  /**
   *
   * @var string
   * @Column(type="string", nullable=true)
   */
  protected string $url;

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
   * Initialize method for model.
   */
  public function initialize() {
    $this->setSchema('public');
    $this->setSource('people');
  }
}
