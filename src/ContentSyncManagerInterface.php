<?php

/**
 * @file
 * Contains \Drupal\default_content\ContentSyncManagerInterface.
 */

namespace Drupal\content_sync;

use Drupal\Core\Entity\EntityInterface;

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
   * @return EntityInterface[]
   *    Array of created entities.
   */
  public function importContentFromFolder($folder, $update_existing = FALSE);

  /**
   * Export all content of specified type, including its with related content.
   *
   * @param string $folder
   *    Root folder where to export content to.
   * @param $entity_type_id
   *    Entity type to export.
   */
  public function exportContentToFolder($folder, $entity_type_id);

}
