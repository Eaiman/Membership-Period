<?php

/**
 * MembershipPeriod.Create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_membership_period_Create_spec(&$spec) {
//  $spec['magicword']['api.required'] = 1;
}

/**
 * MembershipPeriod.Create API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_membership_period_Create($params) {
	try {
		return _civicrm_api3_basic_create("CRM_Membershipperiod_BAO_MembershipPeriod", $params);
	}catch (Exception $e){
		return civicrm_api3_create_error($e->getMessage(), $params);
	}
}
