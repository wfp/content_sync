<?php

/**
 * @file
 * Contains \Drupal\default_content\ContentSyncManagerInterface.
 */

namespace Drupal\content_sync;

/**
 * Interface ContentSyncManagerInterface.
 *
 * @package Drupal\content_sync
 */
interface ContentSyncManagerInterface {

  /**
   * Import default content from specified folder.
   *
   * @param string $folder
   *    Path to default content folder.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *    Array of created entities.
   */
  public function importContentFromFolder($folder);

  /**
   * Export all content of specified type, including its with related content.
   *
   * @param string $folder
   *    Root folder where to export content to.
   * @param string $entity_type_id
   *    Entity type to export.
   *
   * @return array[][]
   *    Array of exported serialized entities, keyed by entity type ID and UUID.
   */
  public function exportContentToFolder($folder, $entity_type_id, $conditions);

}
