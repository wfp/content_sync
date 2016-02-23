<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\ExportNodesCommand.
 */

namespace Drupal\content_sync\Command;

/**
 * Class ExportNodesCommand.
 *
 * @package Drupal\content_sync
 */
class ExportNodesCommand extends AbstractExportCommand {

  /**
   * {@inheritdoc}
   */
  public function getEntityTypeId() {
    return 'node';
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName('content-sync:export:nodes')->setDescription($this->trans('command.content-sync.export.description'));
  }

}
