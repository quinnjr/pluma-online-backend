<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

use \Phalcon\Mvc\Model\Behavior\Timestampable;

namespace PluMA\Behaviors;

class DateTracking extends Timestampable {
  public function __construct(array $options = []) {
    parent::__construct(array_merge($options, [
      'beforeCreate' => [ 'field' => 'created_at', 'format' => 'Y-m-d H:i:s' ],
      'beforeUpdate' => [ 'field' => 'updated_at', 'format' => 'Y-m-d H:i:s' ]
    ]));
  }
}
