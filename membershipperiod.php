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
 *adds a contact tab "Membership Periods" and fetches the content from database.
 */
function membershipperiod_civicrm_tabset($tabsetName, &$tabs, $context) {
	if ($tabsetName == 'civicrm/contact/view') {
		//for contact view page
		$contactID = $context['contact_id'];
		
		$sql = "SELECT count(*) as total
					  FROM civicrm_membershipperiod as mp
					  INNER JOIN civicrm_membership m ON m.id = mp.membership_id
					  WHERE m.contact_id = $contactID";
		$results = CRM_Core_DAO::executeQuery($sql);
		$results->fetch();
		$mp_count = $results->total;
		
		$url = CRM_Utils_System::url('civicrm/membership-period?reset=1&contact_id='
						. $contactID);
		
		$tabs[] = [
			'id' => 'membershipPeriodTab',
			'url' => $url,
			'title' => 'Membership Periods',
			'weight' => 300,
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
		
		$sql = "SELECT * FROM civicrm_membershipperiod
						WHERE end_date=$objectRef->end_date AND membership_id = $objectRef->id";
		$result = CRM_Core_DAO::executeQuery($sql);
		
		if (!($result->fetch())) {
			$queryc = "SELECT MAX(DATE_ADD(end_date, INTERVAL 1 DAY)) AS new_date
								 FROM civicrm_membershipperiod
								 WHERE membership_id = $objectRef->id";
			$resultc = CRM_Core_DAO::executeQuery($queryc);
			
			if ($resultc->fetch()) {
				if (!empty($resultc->new_date)) {
					$query = "INSERT INTO civicrm_membershipperiod (start_date, end_date, membership_id, contribution_id, renewed_date)
										VALUES ('$resultc->new_date', '$objectRef->end_date', $objectRef->id, NULL, NOW())";
				}else {
					$query = "INSERT INTO civicrm_membershipperiod (start_date, end_date, membership_id, contribution_id, renewed_date)
 										VALUES ('$objectRef->start_date', '$objectRef->end_date', $objectRef->id, NULL , NOW())";
				}
				
				CRM_Core_DAO::executeQuery($query);
			}
		}
	} else if ($objectName == "MembershipPayment" && $op == "create") {
		// updates membership period when membership payment is made.
		$sql = "SELECT id FROM civicrm_membershipperiod
							WHERE contribution_id = $objectRef->contribution_id";
		
		$result = CRM_Core_DAO::executeQuery($sql);
		
		if (!($result->fetch())) {
			$query = "UPDATE civicrm_membershipperiod
								SET contribution_id = $objectRef->contribution_id
								WHERE membership_id = $objectRef->membership_id AND contribution_id IS NULL
								ORDER BY id DESC LIMIT 1";
			CRM_Core_DAO::executeQuery($query);
		}
	}
}
