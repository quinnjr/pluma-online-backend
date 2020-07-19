<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Models;

use \Phalcon\Mvc\Model;

class Plugins extends Model {
  /**
   *
   * @var integer
   */
  protected int $id;

  /**
   *
   * @var string
   */
  protected string $name;

  /**
   *
   * @var integer
   */
  protected int $category_id;

  /**
   *
   * @var string
   */
  protected string $description;

  /**
   *
   * @var string
   */
  protected string $github_url;

  /**
   *
   * @var integer
   */
  protected int $language_id;

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

  public function initialize() {
    $this->setSchema('public');
    $this->setSource('plugins');

    $this->hasOne(
      'category_id',
      Categories::class,
      'id',
      [
        'reusable' => true
      ]
    );

    $this->hasOne(
      'language_id',
      Languages::class,
      'id',
      [
        'reuseable' => true
      ]
    );
  }
}
