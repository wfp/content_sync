<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\ExportBlockContentCommand.
 */

namespace Drupal\content_sync\Command;

/**
 * Class ExportBlockContentCommand.
 *
 * @package Drupal\content_sync
 */
class ExportBlockContentCommand extends AbstractExportCommand {

  /**
   * {@inheritdoc}
   */
  public function getEntityTypeId() {
    return 'block_content';
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName('content-sync:export:blocks')->setDescription($this->trans('command.content-sync.export.description'));
  }

}
