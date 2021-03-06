<?php

require_once 'membershipperiod.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function membershipperiod_civicrm_config(&$config) {
	_membershipperiod_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function membershipperiod_civicrm_xmlMenu(&$files) {
	_membershipperiod_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function membershipperiod_civicrm_install() {
	_membershipperiod_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function membershipperiod_civicrm_postInstall() {
	_membershipperiod_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function membershipperiod_civicrm_uninstall() {
	_membershipperiod_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function membershipperiod_civicrm_enable() {
	_membershipperiod_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function membershipperiod_civicrm_disable() {
	_membershipperiod_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function membershipperiod_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
	return _membershipperiod_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function membershipperiod_civicrm_managed(&$entities) {
	_membershipperiod_civix_civicrm_managed($entities);
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
function membershipperiod_civicrm_caseTypes(&$caseTypes) {
	_membershipperiod_civix_civicrm_caseTypes($caseTypes);
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
function membershipperiod_civicrm_angularModules(&$angularModules) {
	_membershipperiod_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function membershipperiod_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
	_membershipperiod_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
 * function membershipperiod_civicrm_preProcess($formName, &$form) {
 *
 * } // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 * function membershipperiod_civicrm_navigationMenu(&$menu) {
 * _membershipperiod_civix_insert_navigation_menu($menu, NULL, array(
 * 'label' => ts('The Page', array('domain' => 'com.eaiman.membershipperiod')),
 * 'name' => 'the_page',
 * 'url' => 'civicrm/the-page',
 * 'permission' => 'access CiviReport,access CiviContribute',
 * 'operator' => 'OR',
 * 'separator' => 0,
 * ));
 * _membershipperiod_civix_navigationMenu($menu);
 * } // */

/**
 * Implements hook_civicrm_tabset().
 *
 * adds a tab "Membership Periods" and fetches the content from database.
 */
function membershipperiod_civicrm_tabset($tabsetName, &$tabs, $context) {
	if ($tabsetName == 'civicrm/contact/view') {
		$mp_count = civicrm_api3('MembershipPeriod', 'getcount', ["contact_id" => $context['contact_id']]);
		$url = CRM_Utils_System::url('civicrm/membership-period?reset=1&contact_id='
						. $context['contact_id']);
		
		$tabs[] = [
			'id' => 'membershipperiod',
			'url' => $url,
			'title' => 'Membership Periods',
			'weight' => 500,
			'count' => $mp_count,
		];
		
	}
}

/**
 * Implements hook_civicrm_post().
 */
function membershipperiod_civicrm_post($op, $objectName, $objectId, &$objectRef) {
	if ($objectName == "Membership" && ($op == "edit" || $op == "create")) {
		// Insert a memebership period record into database
		// when membership is created/renewed.
		$instance = new CRM_Membershipperiod_DAO_MembershipPeriod();
		$instance->whereAdd("end_date = ".$objectRef->end_date);
		$instance->whereAdd("membership_id = ".$objectRef->id);
		$instance->find();
		
		// check the data exist or not
		if (!$instance->fetch()) {
			$instance = new CRM_Membershipperiod_DAO_MembershipPeriod();
			
			// try to find out the start date for the new membership period entry
			$instance->selectAdd();
			$instance->selectAdd("MAX(DATE_ADD(end_date, INTERVAL 1 DAY)) as new_date");
			$instance->whereAdd("membership_id = ".$objectRef->id);
			$instance->find();
			
			if ($instance->fetch()) {
				$params = [];
				if (!empty($instance->new_date)) {
					$params['start_date'] = $instance->new_date;
				}else {
					$params['start_date'] = $objectRef->start_date;
				}
				$params['end_date'] = $objectRef->end_date;
				$params['membership_id'] = $objectRef->id;
				$params['renewed_date'] = date("Y-m-d H:i:s");
				
				civicrm_api3('MembershipPeriod', 'create', $params);
			}
			$instance->free();
		}
		$instance->free();
	} else if ($objectName == "MembershipPayment" && $op == "create") {
		// updates membership period when membership payment is made.
		$instance = new CRM_Membershipperiod_DAO_MembershipPeriod();
		if (!$instance->get("contribution_id", $objectRef->contribution_id)) {
			$instance = new CRM_Membershipperiod_DAO_MembershipPeriod();
			$instance->whereAdd("membership_id = ".$objectRef->membership_id);
			$instance->whereAdd("contribution_id IS NULL");
			$instance->orderBy();
			$instance->orderBy("id DESC");
			$instance->find(TRUE);
//			$instance->fetch();
			$instance->contribution_id = $objectRef->contribution_id;
			$instance->update();
		}
		$instance->free();
	}
}
