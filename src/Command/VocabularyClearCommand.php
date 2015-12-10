<?php

/**
 * @file
 * Contains \Drupal\content_sync\Command\VocabularyClearCommand.
 */

namespace Drupal\content_sync\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Command\ContainerAwareCommand;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Class VocabularyClearCommand.
 *
 * @package Drupal\content_sync
 */
class VocabularyClearCommand extends ContainerAwareCommand {
  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->setName('content_sync:vocabulary-clear')
      ->setDescription($this->trans('command.content_sync.vocabulary-clear.description'))
      ->addArgument('vid', InputArgument::REQUIRED,
        $this->trans('command.content_sync.vocabulary-clear.arguments.vid'));
  }


  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    _content_sync_vocabulary_clear($input->getArgument('vid'));
    $output->writeln("Vocabulary " . $input->getArgument('vid') . " Cleared!");
  }

  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $question = $this->getQuestionHelper();
    $vids = _content_sync_get_entities_ids_by_type_id('taxonomy_vocabulary');

    $vid = $question->ask($input, $output,
      new ChoiceQuestion("Choose vocabulary",
        array_combine(array_keys($vids), array_keys($vids)), 1));
    $input->setArgument('vid', $vid);
  }

}
