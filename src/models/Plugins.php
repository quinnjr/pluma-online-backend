<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Models;

use \Phalcon\Mvc\Model;
use \Phalcon\Mvc\Model\Behavior\Timestampable;

class Plugins extends Model {
  /**
   *
   * @var integer
   */
  protected integer $plu_id;

  /**
   *
   * @var string
   */
  protected string $plu_name;

  /**
   *
   * @var integer
   */
  protected integer $plu_category_id;

  /**
   *
   * @var string
   */
  protected string $plu_description;

  /**
   *
   * @var string
   */
  protected string $plu_github_url;

  /**
   *
   * @var integer
   */
  protected integer $plu_language_id;

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

  public function initialize() {
    $this->setSchema('public');
    $this->setSource('plugins');

    $this->hasOne('plu_category_id', Categories::class, 'cat_id');
    $this->hasOne('plu_language_id', Languages::class, 'lang_id');
  }
}
