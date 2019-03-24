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
 * This is the command and control centre of CiviShare
 */
class CRM_Share_Controller {

  private static $singleton = NULL;

  private $handlers = NULL;

  /**
   * Get the CiviShare controller instance
   *
   * @return CRM_Share_Controller|null
   */
  public static function singleton() {
    if (self::$singleton === NULL) {
      self::$singleton = new CRM_Share_Controller();
    }
    return self::$singleton;
  }

  /**
   * Log a message
   *
   * @param $message  string the message
   * @param $level    string log level (debug, info, warn, error)
   */
  public function log($message, $level = 'info') {
    // TODO: implement log levels
    CRM_Core_Error::debug_log_message("CiviShare: " . $message);
  }

  /**
   * Get the list of active handlers
   *
   * @return null
   */
  public function getHandlers()
  {
    if ($this->handlers === NULL) {
      $this->handlers = [];
      // run the query first
      $query = CRM_Core_DAO::executeQuery("
        SELECT 
          id            AS handler_id, 
          name          AS handler_name, 
          class         AS handler_class, 
          configuration AS handler_configuration
        FROM civicrm_share_handler
        WHERE is_enabled = 1
        ORDER BY weight ASC;");
      while ($query->fetch()) {
        if (class_exists($query->handler_class)) {
          $configuration = json_decode($query->handler_configuration, TRUE);
          if ($configuration === NULL) {
            $this->log("Handler [{$query->handler_class}] has an invalid configuration.", 'error');
            $configuration = [];
          }
          $this->handlers[] = new $query->handler_class($query->handler_id, $query->handler_name, $configuration);
        } else {
          $this->log("Unknown handler class {$query->handler_class}, handler skipped.", 'error');
        }
      }
    }
    return $this->handlers;
  }
}