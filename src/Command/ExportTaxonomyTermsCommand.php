<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\ExportTaxonomyTermsCommand.
 */

namespace Drupal\content_sync\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExportTaxonomyTermsCommand.
 *
 * @package Drupal\content_sync
 */
class ExportTaxonomyTermsCommand extends AbstractExportCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName('content-sync:export:taxonomy-terms')->setDescription($this->trans('command.content_sync.export.taxonomy_term.description'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->contentManager->exportContentToFolder($input->getArgument('folder'), 'taxonomy_term');
    $output->writeln("Taxonomy terms exported to " . $input->getArgument('folder'), OutputInterface::OUTPUT_NORMAL);
  }

}
