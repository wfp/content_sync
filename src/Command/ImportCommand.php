<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\ImportCommand.
 */

namespace Drupal\content_sync\Command;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Command\Command;
use Drupal\Console\Command\moduleTrait;
use Symfony\Component\Console\Question\Question;

/**
 * Class ImportCommand.
 *
 * @package Drupal\content_sync
 */
class ImportCommand extends Command {

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
    $folder = $input->getOption('folder');

    $output->writeln(_content_sync_import($folder) . ' ' . $this->trans('command.content_sync.import.summary'));
    $output->writeln("No errors");

  }


  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $dialog = new QuestionHelper();

    $folder = $input->getOption('folder');
    if (!$folder) {
      $folder = $dialog->ask($input, $output,
        new Question($this->trans('command.content_sync.import.folder_path')));
    }

    $input->setOption('folder', $folder);
  }

}
