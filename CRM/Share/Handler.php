<?php
/*-------------------------------------------------------+
| CiviShare                                              |
| Copyright (C) 2019 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+-------------------------------------------------------*/

/**
 * This is the base class of all CiviShare handers
 */
abstract class CRM_Share_Handler {

  protected $id;
  protected $name;
  protected $configuration;


  /**
   * Generic CRM_Share_Handler constructor.
   * @param $id
   * @param $name
   * @param $configuration
   */
  public function __construct($id, $name, $configuration) {
    $this->id = $id;
    $this->name = $name;
    $this->configuration = $configuration;
  }


  /**
   * If this handler can process pre-hook related change, it can return a record here that
   * will then be passed into createPostHookChange()
   *
   * @param $op          string operator
   * @param $objectName  string object name
   * @param $id          int    object ID
   * @param $params      array  change parameters
   *
   * @return array before_record
   */
  public function createPreHookRecord($op, $objectName, $id, $params) {
    return NULL;
  }

  /**
   * Generate a change record, if a change is detected
   *
   * @param $pre_record  array  previously recorded change
   * @param $op          string operator
   * @param $objectName  string object name
   * @param $id          int    object ID
   * @param $objectRef   mixed  depends on the hook (afaik)
   */
  public function createPostHookChange($pre_record, $op, $objectName, $id,  $objectRef) {
    return;
  }
}