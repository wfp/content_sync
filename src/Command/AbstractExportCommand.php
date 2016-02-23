<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\AbstractExportCommand.
 */

namespace Drupal\content_sync\Command;

use Drupal\Console\Command\ContainerAwareCommand;
use Drupal\content_sync\ContentSyncManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class AbstractExportCommand.
 *
 * @package Drupal\content_sync\Command
 */
abstract class AbstractExportCommand extends ContainerAwareCommand {

  /**
   * Content manager service.
   *
   * @var ContentSyncManagerInterface
   */
  protected $contentManager = NULL;

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    parent::configure();
    $this->contentManager = $this->getService('content_sync.manager');
    $this->addArgument('folder', NULL, InputArgument::REQUIRED, '.')
      ->addOption('bundle', NULL, InputOption::VALUE_OPTIONAL,
        $this->trans('command.content_sync.export.options.bundle'));
  }

}
