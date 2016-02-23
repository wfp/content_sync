<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\ExportTaxonomyTermsCommand.
 */

namespace Drupal\content_sync\Command;

/**
 * Class ExportTaxonomyTermsCommand.
 *
 * @package Drupal\content_sync
 */
class ExportTaxonomyTermsCommand extends AbstractExportCommand {

  /**
   * {@inheritdoc}
   */
  public function getEntityTypeId() {
    return 'taxonomy_term';
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName('content-sync:export:taxonomy-terms')->setDescription($this->trans('command.content-sync.export.description'));
  }

}
