<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\ImportCommand.
 */

namespace Drupal\content_sync\Command;

use Drupal\Console\Annotations\DrupalCommand;
use Drupal\Console\Core\Command\Shared\ContainerAwareCommandTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportCommand.
 *
 * @DrupalCommand (
 *     extension="content_sync",
 *     extensionType="module"
 * )
 */
class ImportCommand extends AbstractCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->setName('content-sync:import')
      ->setDescription($this->trans('command.content-sync.import.description'));
    $this->addOption('update', 'u', InputOption::VALUE_NONE, $this->trans('command.content-sync.import.options.update'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->getContentManager()->importContentFromFolder($input->getArgument('folder'), $input->getOption('update'));
    $output->writeln("Content imported from " . $input->getArgument('folder'), OutputInterface::OUTPUT_NORMAL);
  }

}
