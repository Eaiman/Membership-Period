<?php

/**
 * MembershipPeriod.Get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 *
 * @author Eaiman Shoshi
 */
function _civicrm_api3_membership_period_Get_spec(&$spec) {
  $spec['contact_id']['api.required'] = 1;
}

/**
 * MembershipPeriod.Get API
 *
 * @param $contact_id
 *
 * @return array API result descriptor
 * @internal param array $params
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 */
/**
 * @param $params
 *
 * @return array
 * @internal param $contact_id
 *
 */
function civicrm_api3_membership_period_Get($params) {
	try {
		$records = CRM_Membershipperiod_BAO_MembershipPeriod::getValues($params);
		return civicrm_api3_create_success($records, $params, 'MembershipPeriod', "get");
	}catch (Exception $e){
		return civicrm_api3_create_error($e->getMessage(), $params);
	}
//	return $records;
}

