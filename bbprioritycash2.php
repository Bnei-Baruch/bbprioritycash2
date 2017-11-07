<?php

require_once 'bbprioritycash2.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function bbprioritycash2_civicrm_config(&$config) {
  _bbprioritycash2_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function bbprioritycash2_civicrm_xmlMenu(&$files) {
  _bbprioritycash2_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function bbprioritycash2_civicrm_install() {
  $params = array(
    'version' => 3,
    'name' => 'BBPC',
    'title' => 'BB Priority CASH Payment Processor',
    'description' => 'Register Cash Payment in Priority',
    'class_name' => 'BBPriorityCash2',
    'billing_mode' => 'notify', // Corresponds to the Processor Type: Form (1), Button (2), Special (3) or Notify (4)
    'user_name_label' => 'User',
    'password_label' => 'Password',
    'signature_label' => 'Terminal',
    //    'subject_label' => 'Subject',
    'url_site_default' => 'http://www.kab.co.il/images/hebmain/logo1.png',
    //    'url_api_default' => 'http://www.example.co.il/',
    //    'url_recur_default' => 'http://www.example.co.il/',
    //    'url_button_default' => 'http://www.example.co.il/',
    //    'url_site_test_default' => 'http://www.example.co.il/',
    'url_site_test_default' => 'http://www.kab.co.il/images/hebmain/logo1.png',
    //    'url_api_test_default' => 'http://www.example.co.il/',
    //    'url_recur_test_default' => 'http://www.example.co.il/',
    //    'url_button_test_default' => 'http://www.example.co.il/',
    'is_recur' => 0,
    'payment_type' => 1, // Credit Card (1) or Debit Card (2)
  );

  civicrm_api('PaymentProcessorType', 'create', $params);
  _bbprioritycash2_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function bbprioritycash2_civicrm_postInstall() {
  _bbprioritycash2_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function bbprioritycash2_civicrm_uninstall() {
  $params = array(
    'version' => 3,
    'sequential' => 1,
    'name' => 'BBPC',
  );
  $result = civicrm_api('PaymentProcessorType', 'get', $params);
  if ($result["count"] == 1) {
    $params = array(
      'version' => 3,
      'sequential' => 1,
      'id' => $result["id"],
    );
    civicrm_api('PaymentProcessorType', 'delete', $params);
  }

  _bbprioritycash2_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function bbprioritycash2_civicrm_enable() {
  _bbprioritycash2_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function bbprioritycash2_civicrm_disable() {
  _bbprioritycash2_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function bbprioritycash2_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _bbprioritycash2_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function bbprioritycash2_civicrm_managed(&$entities) {
  _bbprioritycash2_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function bbprioritycash2_civicrm_caseTypes(&$caseTypes) {
  _bbprioritycash2_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function bbprioritycash2_civicrm_angularModules(&$angularModules) {
  _bbprioritycash2_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function bbprioritycash2_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _bbprioritycash2_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
 * function bbprioritycash2_civicrm_preProcess($formName, &$form) {
 *
 * } // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 * function bbprioritycash2_civicrm_navigationMenu(&$menu) {
 * _bbprioritycash2_civix_insert_navigation_menu($menu, NULL, array(
 * 'label' => ts('The Page', array('domain' => 'info.kabbalah.payment.bbprioritycash2')),
 * 'name' => 'the_page',
 * 'url' => 'civicrm/the-page',
 * 'permission' => 'access CiviReport,access CiviContribute',
 * 'operator' => 'OR',
 * 'separator' => 0,
 * ));
 * _bbprioritycash2_civix_navigationMenu($menu);
 * } // */
