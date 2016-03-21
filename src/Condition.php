<?php

/**
 * @file
 * Contains \Drupal\default_content\Condition.
 */

namespace Drupal\content_sync;

/**
 * Class Condition.
 *
 * @package Drupal\content_sync
 */
class Condition {

  /**
   * Field name.
   *
   * @var string
   */
  public $field;

  /**
   * Field value.
   *
   * @var string
   */
  public $value;

  /**
   * Operator.
   *
   * @var string
   */
  public $operator;

  /**
   * Language code.
   *
   * @var string
   */
  public $langcode;

  /**
   * Condition constructor.
   */
  public function __construct($field, $value, $operator, $langcode = 'en') {
    $this->field = $field;
    $this->value = $value;
    $this->operator = $operator;
    $this->langcode = $langcode;
  }

}
