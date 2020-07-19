<?php
// Copyright (c) 2019-2020 FIUBioRG
// SPDX-License-Identifier: MIT

namespace PluMA\Controllers;

use \Phalcon\Mvc\Micro\Exception;
use \Phalcon\Mvc\Model\Query;
use \Carbon\Carbon;

use PluMA\Models\Plugins;
use PluMA\Models\Categories;
use PluMA\Models\Languages;

/**
 *
 */
class PluginsController extends AbstractController {

  /**
   * Returns a list of plugins.
   *
   * @return array
   */
  public function getAction() {
    try {
      $plugins = $this->modelsManager->createBuilder()
        ->addFrom(Plugins::class, 'p')
        ->columns(
          "p.id AS id,
          p.name AS name,
          p.description as description,
          p.github_url as github_url,
          c.id as category_id,
          l.name as language_name,
          p.updated_at"
        )
        ->innerJoin(Categories::class, 'p.category_id = c.id', 'c')
        ->innerJoin(Languages::class, 'p.language_id = l.id', 'l')
        ->orderBy('p.id')
        ->getQuery()
        ->execute();

      if (\is_null($plugins)) {
        return [];
      }

      return $plugins->toArray();
    } catch (\PDOException $e) {
      throw $e;
    }
  }

  /**
   * Create a plugin object.
   *
   * @return void
   */
  public function createAction() {

  }

  /**
   * Update an existing plugin.
   *
   * @param int $id
   *
   * @return bool The status of the update action.
   */
  public function updateAction(int $id): bool {

  }

  /**
   * Delete an existing plugin.
   *
   * @param int $id
   *
   * @return bool The status of the delete action.
   */
  public function deleteAction(int $id): bool {

  }
}
