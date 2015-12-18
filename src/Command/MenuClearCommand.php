<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\MenuClearCommand.
 */

namespace Drupal\content_sync\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Command\ContainerAwareCommand;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Class MenuClearCommand.
 *
 * @package Drupal\content_sync
 */
class MenuClearCommand extends ContainerAwareCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->setName('content_sync:menu-clear')
      ->setDescription($this->trans('command.content_sync.menu-clear.description'))
      ->addArgument('menu_name', InputArgument::REQUIRED,
        $this->trans('command.content_sync.menu-clear.arguments.menu_name'));
  }


  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    _content_sync_menu_clear($input->getArgument('menu_name'));
    $output->writeln("Menu " . $input->getArgument('menu_name') . " Cleared!");
  }

  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $question = $this->getQuestionHelper();
    $menu_ids = _content_sync_get_entities_ids_by_type_id('menu');

    $menu_name = $question->ask($input, $output,
      new ChoiceQuestion("Choose menu",
        array_combine(array_keys($menu_ids), array_keys($menu_ids)), 1));
    $input->setArgument('menu_name', $menu_name);
  }

}
