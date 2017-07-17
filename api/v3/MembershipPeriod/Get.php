<?php

/**
 * MembershipPeriod.Get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
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
 * @param $contact_id
 *
 * @return array
 */
function civicrm_api3_membership_period_Get($params) {
	try {
		$contact_id = $params['contact_id'];
		$sql = "SELECT mp.start_date, mp.end_date, mp.renewed_date, mp.contribution_id
				  FROM civicrm_membershipperiod mp
				  INNER JOIN civicrm_membership m ON m.id = mp.membership_id
			    WHERE m.contact_id = $contact_id";
		
		//	$args = ['useWhereAsOn' => false];
		//	$membership = new CRM_Member_DAO_Membership();
		//	$membership->contact_id =$contact_id;
		//	$membership->whereAdd("id = civicrm_membershipperiod.membership_id");
		//	$results = new CRM_Membershipperiod_DAO_MembershipPeriod();
		//	$results->joinAdd($membership);
		//	$results->find();
		
		$results = CRM_Core_DAO::executeQuery($sql);
		$records = [];
		$i = 0;
		while ($results->fetch()) {
			$records[$i]['start_date'] = $results->start_date;
			$records[$i]['end_date'] = $results->end_date;
			$records[$i]['renewed_date'] = $results->renewed_date;
			$records[$i]['contribution_id'] = $results->contribution_id;
			$i++;
		}
		return civicrm_api3_create_success($records, $params, 'MembershipPeriod', "get");
	}catch (Exception $e){
		return civicrm_api3_create_error($e->getMessage(), $params);
	}
//	return $records;
}

