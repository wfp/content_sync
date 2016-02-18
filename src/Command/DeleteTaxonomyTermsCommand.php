<?php
/**
 * @file
 * Contains \Drupal\content_sync\Command\DeleteTaxonomyTermsCommand.
 */

namespace Drupal\content_sync\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Command\ContainerAwareCommand;
use Symfony\Component\Console\Question\ChoiceQuestion;
/**
 * Class DeleteTaxonomyTermsCommand.
 *
 * @package Drupal\content_sync
 */
class DeleteTaxonomyTermsCommand extends ContainerAwareCommand {
  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->setName('content_sync:delete-taxonomy-terms')
      ->setDescription($this->trans('command.content_sync.delete-taxonomy-terms.description'))
      ->addArgument('vid', InputArgument::REQUIRED,
        $this->trans('command.content_sync.delete-taxonomy-terms.arguments.vid'));
  }
  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    _content_sync_delete_taxonomy_terms($input->getArgument('vid'));
    $output->writeln("Terms deleted!");
  }
  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $question = new QuestionHelper();
    $vids = _content_sync_get_entities_ids_by_type_id('taxonomy_vocabulary');
    $vid = $question->ask($input, $output,
      new ChoiceQuestion("Choose vocabulary",
        array_combine(array_keys($vids), array_keys($vids)), 1));
    $input->setArgument('vid', $vid);
  }

}
