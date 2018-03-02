<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\AbstractCommand.
 */

namespace Drupal\content_sync\Command;

use Drupal\Console\Core\Command\Command;
use Drupal\Console\Core\Command\Shared\ContainerAwareCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class AbstractCommand.
 *
 * @package Drupal\content_sync\Command
 */
abstract class AbstractCommand extends Command {

  use ContainerAwareCommandTrait;

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
    $this->addArgument('folder', InputArgument::REQUIRED, $this->trans('command.content-sync.arguments.folder'));
  }

  /**
   * @return \Drupal\content_sync\ContentSyncManagerInterface
   */
  protected function getContentManager() {
    $this->contentManager = $this->get('content_sync.manager');
    return $this->contentManager;
  }

}
