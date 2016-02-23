<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\ExportCommandInterface.
 */

namespace Drupal\content_sync\Command;

/**
 * Interface ExportCommandInterface.
 *
 * @package Drupal\content_sync\Command
 */
interface ExportCommandInterface {

  /**
   * Get entity type ID to export.
   *
   * @return string
   *    Entity type ID to export.
   */
  public function getEntityTypeId();

}
