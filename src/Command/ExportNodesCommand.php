<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\ExportNodesCommand.
 */

namespace Drupal\content_sync\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExportNodesCommand.
 *
 * @package Drupal\content_sync
 */
class ExportNodesCommand extends AbstractExportCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName('content-sync:export:nodes')->setDescription($this->trans('command.content_sync.export.nodes.description'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->contentManager->exportContentToFolder($input->getArgument('folder'), 'node');
    $output->writeln("Content exported to " . $input->getArgument('folder'), OutputInterface::OUTPUT_NORMAL);
  }

}
