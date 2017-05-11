<?php

/**
 * @file
 * Contains \Drupal\default_content\ContentSyncManager.
 */

namespace Drupal\content_sync;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\default_content\DefaultContentManager;
use Drupal\default_content\Event\DefaultContentEvents;
use Drupal\default_content\Event\ImportEvent;

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

    // @see \Drupal\default_content\DefaultContentManager::importContent()
    $created = [];
    if (file_exists($folder)) {
      $file_map = array();
      foreach ($this->entityManager->getDefinitions() as $entity_type_id => $entity_type) {
        $reflection = new \ReflectionClass($entity_type->getClass());
        // We are only interested in importing content entities.
        if ($reflection->implementsInterface('\Drupal\Core\Config\Entity\ConfigEntityInterface')) {
          continue;
        }
        if (!file_exists($folder . '/' . $entity_type_id)) {
          continue;
        }
        $files = $this->scanner()->scan($folder . '/' . $entity_type_id);
        // Default content uses drupal.org as domain.
        // @todo Make this use a uri like default-content:.
        $this->linkManager->setLinkDomain(static::LINK_DOMAIN);
        // Parse all of the files and sort them in order of dependency.
        foreach ($files as $file) {
          $contents = $this->parseFile($file);
          // Decode the file contents.
          $decoded = $this->serializer->decode($contents, 'hal_json');
          // Get the link to this entity.
          $item_uuid = $decoded['uuid'][0]['value'];

          // Throw an exception when this UUID already exists.
          if (isset($file_map[$item_uuid])) {
            $args = array(
              '@uuid' => $item_uuid,
              '@first' => $file_map[$item_uuid]->uri,
              '@second' => $file->uri,
            );
            // Reset link domain.
            $this->linkManager->setLinkDomain(FALSE);
            throw new \Exception(new FormattableMarkup('Default content with uuid @uuid exists twice: @first @second', $args));
          }

          // Store the entity type with the file.
          $file->entity_type_id = $entity_type_id;
          // Store the file in the file map.
          $file_map[$item_uuid] = $file;
          // Create a vertex for the graph.
          $vertex = $this->getVertex($item_uuid);
          $this->graph[$vertex->id]['edges'] = [];
          if (empty($decoded['_embedded'])) {
            // No dependencies to resolve.
            continue;
          }
          // Here we need to resolve our dependencies:
          foreach ($decoded['_embedded'] as $embedded) {
            foreach ($embedded as $item) {
              $uuid = $item['uuid'][0]['value'];
              $edge = $this->getVertex($uuid);
              $this->graph[$vertex->id]['edges'][$edge->id] = TRUE;
            }
          }
        }
      }

      // @todo what if no dependencies?
      $sorted = $this->sortTree($this->graph);
      foreach ($sorted as $link => $details) {
        if (!empty($file_map[$link])) {
          $file = $file_map[$link];
          $entity_type_id = $file->entity_type_id;
          $resource = $this->resourcePluginManager->getInstance(array('id' => 'entity:' . $entity_type_id));
          $definition = $resource->getPluginDefinition();
          $contents = $this->parseFile($file);
          $class = $definition['serialization_class'];
          $entity = $this->serializer->deserialize($contents, $class, 'hal_json', array('request_method' => 'POST'));
          $entity->enforceIsNew(TRUE);
          $entity->save();
          $created[$entity->uuid()] = $entity;
        }
      }
      $this->eventDispatcher->dispatch(DefaultContentEvents::IMPORT, new ImportEvent($created, 'content_sync'));
    }
    // Reset the tree.
    $this->resetTree();
    // Reset link domain.
    $this->linkManager->setLinkDomain(FALSE);
    return $created;
  }


  /**
   * {@inheritdoc}
   */
  public function exportContentToFolder($folder, $entity_type_id, $conditions = NULL) {
    $condition = NULL;
    if ($conditions) {
      $condition = $this->getConditionInstanceFromConditionsString($conditions);
    }

    $serialized_by_type = $this->getSerializedEntities($entity_type_id, $condition);
    foreach ($serialized_by_type as $entity_type_id => $serialized_entities) {
      foreach ($serialized_entities as $entity_uuid => $serialized_entity) {
        $entity_bundle = $this->getSerializedEntityBundle($serialized_entity);
        // Ensure that the folder per entity type exists.
        $entity_type_folder = "$folder/$entity_type_id/$entity_bundle";
        file_prepare_directory($entity_type_folder, FILE_CREATE_DIRECTORY);
        file_put_contents($entity_type_folder . '/' . $entity_uuid . '.json', $serialized_entity);
      }
    }

    return $serialized_by_type;
  }


  /**
   * Parse condition argument.
   *
   * @param string $conditions
   *    Condition string.
   *
   * @return \Drupal\content_sync\Condition
   *    Condition instance derived from condition string.
   */
  public function getConditionInstanceFromConditionsString($conditions) {
    $values    = explode(',', $conditions);
    $condition = new \Drupal\content_sync\Condition($values[0], $values[1], $values[2], $values[3]);
    return $condition;
  }


  /**
   * Return serialized entities, along with their references.
   *
   * @param string $entity_type_id
   *    Entity type ID.
   * @param object $condition
   *    of Drupal\content_sync\Condition objects.
   *    Instance of Drupal\content_sync\Condition objects.
   *
   * @return array[][]
   *    Array of serialized entities, keyed by entity type ID and UUID.
   */
  private function getSerializedEntities($entity_type_id, $condition = NULL) {
    $return = [];

    if (empty($condition)) {
      $entities = $this->entityManager->getStorage($entity_type_id)->loadMultiple();
    }
    else {
      $query = $this->entityManager->getStorage($entity_type_id)->getQuery();
      $query->condition($condition->field, $condition->value, $condition->operator, $condition->langcode);

      $entities_ids = $query->execute();
      $entities = $this->entityManager->getStorage($entity_type_id)->loadMultiple($entities_ids);
    }

    foreach ($entities as $entity) {
      foreach ($this->exportContentWithReferences($entity_type_id, $entity->id()) as $type => $list) {
        foreach ($list as $uuid => $content) {
          $return[$type][$uuid] = $content;
        }
      }
    }

    return $return;
  }


  /**
   * Return serialized entity bundle.
   *
   * @param string $serialized_entity
   *    Serialized entity.
   *
   * @return string
   *    Bundle ID.
   */
  private function getSerializedEntityBundle($serialized_entity) {
    $data  = $this->serializer->decode($serialized_entity, 'hal_json');
    $parts = explode('/', $data['_links']['type']['href']);
    return array_pop($parts);
  }

}
