<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\ExportEntitiesCommand.
 */

namespace Drupal\content_sync\Command;

use Drupal\Console\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Drupal\Console\Command\moduleTrait;

/**
 * Class ExportEntitiesCommand.
 *
 * @package Drupal\content_sync
 */
class ExportCommand extends ContainerAwareCommand {

  use moduleTrait;


  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->setName('content_sync:export')
      ->setDescription($this->trans('command.content_sync.export.description'))
      ->addArgument('entity_type', InputArgument::REQUIRED,
        $this->trans('command.content_sync.export.arguments.entity_type'))
      ->addArgument('menu_name', InputArgument::OPTIONAL,
        $this->trans('command.content_sync.export.arguments.menu_name'))
      ->addOption('module_name', NULL, InputOption::VALUE_REQUIRED,
        $this->trans('command.content_sync.export.arguments.module_name'))
      ->addOption('bundle', NULL, InputOption::VALUE_OPTIONAL,
        $this->trans('command.content_sync.export.options.bundle'), 'none')
      ->addArgument('vid', NULL, InputArgument::REQUIRED,
        $this->trans('command.content_sync.export.options.vid'));
  }


  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    if ($input->getArgument('entity_type') == 'menu_link_content') {
      _content_sync_export_menu_items($input->getArgument('menu_name'),
        $input->getOption('module_name'));
    }

    switch ($input->getArgument('entity_type')) {
      case 'menu_link_content':
        _content_sync_export_menu_items($input->getArgument('menu_name'),
          $input->getOption('module_name'));
        break;

      default:
        _content_sync_export_taxonomy_terms($input->getArgument('vid'),
          $input->getOption('module_name'));
    }

    $output->writeln("Find exported content in module " . $input->getOption('module_name'));
  }


  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $question = $this->getQuestionHelper();
    $entities = $this->getEntityTypes();

    $entity_type = $question->ask($input, $output,
      new ChoiceQuestion($this->trans('command.content_sync.export.arguments.entity_type'),
        array_combine(array_keys($entities), array_keys($entities)), 1));
    $input->setArgument('entity_type', $entity_type);

    switch ($entity_type) {
      case 'menu_link_content':
        $menu_ids  = _content_sync_get_entities_ids_by_type_id('menu');
        $menu_name = $question->ask($input, $output,
          new ChoiceQuestion($this->trans('command.content_sync.export.arguments.menu_name'),
            array_combine(array_keys($menu_ids), array_keys($menu_ids)), 1));
        $input->setArgument('menu_name', $menu_name);
        break;

      case 'taxonomy_term':
        $taxonomy_vids = _content_sync_get_entities_ids_by_type_id('taxonomy_vocabulary');
        $vid           = $question->ask($input, $output,
          new ChoiceQuestion($this->trans('command.content_sync.export.arguments.vid'),
            array_combine(array_keys($taxonomy_vids),
              array_keys($taxonomy_vids)), 1));
        $input->setArgument('vid', $vid);
        break;
    }

    $dialog      = $this->getDialogHelper();
    $module_name = $input->getOption('module_name');
    if (!$module_name) {
      // @see Drupal\Console\Command\module_nameTrait::module_nameQuestion
      $module_name = $this->moduleQuestion($output, $dialog);
    }

    $input->setOption('module_name', $module_name);

  }


  /**
   * Returns array of entity types.
   */
  protected function getEntityTypes() {
    return \Drupal::entityTypeManager()->getDefinitions();
  }

}
