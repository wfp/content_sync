<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\AbstractExportCommand.
 */

namespace Drupal\content_sync\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractExportCommand.
 *
 * @package Drupal\content_sync\Command
 */
abstract class AbstractExportCommand extends AbstractCommand implements ExportCommandInterface {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->addOption('bundle', 'b', InputOption::VALUE_OPTIONAL, $this->trans('command.content-sync.export.options.bundle'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->contentManager->exportContentToFolder($input->getArgument('folder'), $this->getEntityTypeId(), $input->getArgument('conditions_string'));
    $output->writeln("Content exported to " . $input->getArgument('folder'), OutputInterface::OUTPUT_NORMAL);
  }

}
