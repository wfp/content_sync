<?php

/**
 * @file
 * Contains \Drupal\default_content\ContentSyncManager.
 */

namespace Drupal\content_sync;

use Drupal\default_content\DefaultContentManager;
use Drupal\default_content\Event\DefaultContentEvents;
use Drupal\default_content\Event\ImportFromFolderEvent;

/**
 * Class ContentSyncManager.
 *
 * @package Drupal\default_content
 */
class ContentSyncManager extends DefaultContentManager implements ContentSyncManagerInterface {

  /**
   * {@inheritdoc}
   */
  public function importContentFromFolder($folder, $update_existing = FALSE) {
    // We fully scan the provided folder without discriminating per entity type.
    $this->buildGraph($folder);
    $entities = $this->createEntities($update_existing);
    $this->eventDispatcher->dispatch(DefaultContentEvents::IMPORT, new ImportFromFolderEvent($entities, $folder));
    return $entities;
  }

  /**
   * {@inheritdoc}
   */
  public function exportContentToFolder($folder, $entity_type_id, $entity_bundle_id = NULL) {
    $serialized_by_type = $this->getSerializedEntities($entity_type_id);
    foreach ($serialized_by_type as $entity_type_id => $serialized_entities) {
      $bundle_key = $this->entityManager->getDefinition($entity_type_id)->getKeys()['bundle'];
      /** @var \Drupal\Core\Entity\EntityInterface $entity */
      foreach ($serialized_entities as $entity) {
        // Ensure that the folder per entity type exists.
//          $entity_type_folder = "$folder/$entity_type_id/$entity_bundle";
//          file_prepare_directory($entity_type_folder, FILE_CREATE_DIRECTORY);
//          file_put_contents($entity_type_folder . '/' . $entity_id . '.json', $serialized_entity);
      }
    }

  }

  /**
   * Return serialized entities, along with their references.
   *
   * @param $entity_type_id
   *    Entity type ID.
   *
   * @return array[][]
   *    Array of encoded entities, keyed by entity type ID and UUID.
   */
  private function getSerializedEntities($entity_type_id) {
    $return = [];
    $entities = $this->entityManager->getStorage($entity_type_id)->loadMultiple();
    foreach ($entities as $entity) {
      $referenced_entities = $this->exportContentWithReferences($entity_type_id, $entity->id());
      $return = array_merge($return, $referenced_entities);
    }
    return $return;
  }

  /**
   * Create entities given a pre-populated graph and file map.
   *
   * @param bool $update_existing
   *    Replace existing entities if TRUE.
   *
   * @return \Drupal\Core\Config\Entity\ConfigEntityInterface[]
   *    List of created entities.
   */
  public function createEntities($update_existing = FALSE) {
    $created = array();

    $sorted = $this->sortTree($this->graph);
    foreach ($sorted as $link => $details) {
      if (!empty($this->fileMap[$link])) {
        $file           = $this->fileMap[$link];
        $entity_type_id = $file->entity_type_id;
        $resource       = $this->resourcePluginManager->getInstance(array('id' => 'entity:' . $entity_type_id));
        $definition     = $resource->getPluginDefinition();
        $contents       = $this->parseFile($file);
        $class          = $definition['serialization_class'];
        $entity         = $this->serializer->deserialize($contents, $class, 'hal_json', array('request_method' => 'POST'));
        $entity->enforceIsNew(TRUE);
        // Allow existing entities overwrite.
        // More info at: https://www.drupal.org/node/2640734#comment-10699416
        $existing_entity = $this->entityRepository->loadEntityByUuid($entity_type_id, $entity->uuid());
        if ($update_existing && $existing_entity) {
          // Delete first an existing entity with the same uuid.
          $existing_entity->delete();
        }
        if (!$existing_entity || $update_existing) {
          $entity->save();
          $created[$entity->uuid()] = $entity;
        }
      }
    }

    // Reset the tree.
    $this->resetTree();
    // Reset link domain.
    $this->linkManager->setLinkDomain(FALSE);
    return $created;
  }
}
