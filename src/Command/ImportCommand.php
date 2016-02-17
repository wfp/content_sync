<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\ImportCommand.
 */

namespace Drupal\content_sync\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Command\ContainerAwareCommand;
use Drupal\Console\Command\moduleTrait;
use Symfony\Component\Console\Question\Question;
use Drupal\Console\Helper\DialogHelper;

/**
 * Class ImportCommand.
 *
 * @package Drupal\content_sync
 */
class ImportCommand extends ContainerAwareCommand {

  use moduleTrait;


  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->setName('content_sync:import')
      ->setDescription($this->trans('command.content_sync.import.description'))
      ->addOption('folder', '', InputOption::VALUE_OPTIONAL, "folder");
  }


  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $folder  = $input->getOption('folder');
    $options = array(
      'type'        => 'folder',
      'folder_path' => $folder,
    );
    _content_sync_import($options);

    $output->writeln(_content_sync_import($options) . ' ' . $this->trans('command.content_sync.import.summary'));
    $output->writeln("No errors");

  }


  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $dialog = $this->getDialogHelper();
    $question = $this->getQuestionHelper();

    $folder = $input->getOption('folder');
    if (!$folder) {
      $folder = $dialog->ask($output, $this->trans('command.content_sync.import.folder_path'));
    }

    $input->setOption('folder', $folder);
  }

}
