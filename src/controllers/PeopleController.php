<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Controllers;

use PluMA\Models\People;

class PeopleController extends AbstractController {

  /**
   * Returns a list of people.
   *
   * @return array
   */
  public function getAction() {
    try {
      $people = People::find([
        'conditions' => '',
        'bind' => [],
        'order' => 'id'
      ]);

      if (!$people || $people === null) {
        return [];
      }

      return $people->toArray();
    } catch (\PDOException $e) {
      throw $e;
    }
  }

  /**
   * Create a person object.
   */
  public function createAction() {

  }

  /**
   * Update an existing person.
   *
   * @param int $id
   */
  public function updateAction(int $id) {

  }

  /**
   * Delete an existing person.
   *
   * @param int $id
   */
  public function deleteAction(int $id) {

  }
}
