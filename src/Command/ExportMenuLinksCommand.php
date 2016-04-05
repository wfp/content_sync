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
class ExportMenuLinksCommand extends AbstractExportCommand {

  /**
   * {@inheritdoc}
   */
  public function getEntityTypeId() {
    return 'menu_link_content';
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName('content-sync:export:menu-links')->setDescription($this->trans('command.content-sync.export.description'));
  }

}
