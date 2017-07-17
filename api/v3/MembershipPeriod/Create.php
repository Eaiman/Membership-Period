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
//	return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params, "MembershipPeriod");
	//	$query = "INSERT INTO civicrm_membershipperiod (start_date, end_date, membership_id, contribution_id, renewed_date)
	// 						VALUES ('".$params['start_date']."', '".$params['end_date']."', ".
	//		        $params['membership_id'].", ".$params['contribution_id'].", ".
	//		        $params['renewed_date'].")";
	//	CRM_Core_DAO::executeQuery($query);
	//
	try {
		$instance = new CRM_Membershipperiod_DAO_MembershipPeriod();
		$instance->copyValues($params);
		$instance->save();
		
		return civicrm_api3_create_success($instance, $params, 'MembershipPeriod',
			"create", $instance);
	}catch (Exception $e){
		return civicrm_api3_create_error($e->getMessage(), $params);
	}
}
