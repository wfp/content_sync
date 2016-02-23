<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\AbstractCommand.
 */

namespace Drupal\content_sync\Command;

use Drupal\Console\Command\ContainerAwareCommand;
use Drupal\content_sync\ContentSyncManagerInterface;
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
   * @var ContentSyncManagerInterface
   */
  protected $contentManager = NULL;

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->contentManager = $this->getService('content_sync.manager');
    $this->addArgument('folder', InputArgument::OPTIONAL, $this->trans('command.content-sync.arguments.folder'), '.');
  }

}
