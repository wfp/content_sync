<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\AbstractCommand.
 */

namespace Drupal\content_sync\Command;

use Drupal\Console\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class AbstractCommand.
 *
 * @package Drupal\content_sync\Command
 */
abstract class AbstractCommand extends ContainerAwareCommand {

  /**
   * Content manager service.
   *
   * @var \Drupal\content_sync\ContentSyncManagerInterface
   */
  protected $contentManager = NULL;

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->contentManager = $this->getService('content_sync.manager');
    $this->addArgument('folder', InputArgument::REQUIRED, $this->trans('command.content-sync.arguments.folder'));
  }

}
