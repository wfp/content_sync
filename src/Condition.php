<?php

/**
 * @file
 * Contains \Drupal\default_content\Condition.
 */

namespace Drupal\content_sync;

class Condition {

  public $field;

  public $value;

  public $operator;

  public $langcode;


  public function __construct($field, $value, $operator, $langcode = 'en') {
    $this->field   = $field;
    $this->value    = $value;
    $this->operator = $operator;
    $this->langcode = $langcode;
  }

}