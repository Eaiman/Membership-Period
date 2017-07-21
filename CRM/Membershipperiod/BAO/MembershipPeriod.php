<?php

class CRM_Membershipperiod_BAO_MembershipPeriod extends CRM_Membershipperiod_DAO_MembershipPeriod {
	
	/**
	 * Create a new MembershipPeriod based on array-data
	 *
	 * @param array $params key-value pairs
	 *
	 * @return \CRM_Membershipperiod_DAO_MembershipPeriod|NULL
	 * @throws \Exception
	 *
	 * @author Eaiman Shoshi
	 */
  public static function Create($params) {
  	try {
			$className = 'CRM_Membershipperiod_DAO_MembershipPeriod';
			$entityName = 'MembershipPeriod';
			$hook = empty($params['id']) ? 'create' : 'edit';
		
			CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
			$instance = new $className();
			$instance->copyValues($params);
			$instance->save();
			CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);
		
			return $instance;
		}catch (Exception $e){
  		throw new Exception($e->getMessage(), $e->getCode());
		}
  }
  
  public static function getValues($params){
		try {
			$contact_id = $params['contact_id'];
			$sql = "SELECT mp.*
							FROM civicrm_membershipperiod as mp
							INNER JOIN civicrm_membership as m ON m.id = mp.membership_id
							WHERE m.contact_id = $contact_id";
			
			// this part of code is for use the `joinAdd` function.
			// just need to create a .inc fuction to link up membership and membershipperiod table.
//			$membership = new CRM_Member_DAO_Membership();
//			$results = new CRM_Membershipperiod_DAO_MembershipPeriod();
//			$results->joinAdd($membership, 'INNER');
//			$results->selectAdd('civicrm_membershipperiod.*');
//			$results->whereAdd("civicrm_membershipperiod.membership_id = civicrm_membership.id
//			                   AND civicrm_membership.contact_id = {$contact_id}");
//			$results->find();
		
			$results = CRM_Core_DAO::executeQuery($sql);
			
			$records = [];
			$i = 0;
			while ($results->fetch()) {
				$records[$i]['start_date'] = $results->start_date;
				$records[$i]['end_date'] = $results->end_date;
				$records[$i]['renewed_date'] = $results->renewed_date;
				$records[$i]['contribution_id'] = $results->contribution_id;
				$records[$i]['membership_id'] = $results->membership_id;
				$records[$i]['id'] = $results->id;
				$i++;
			}
			return $records;
		}catch (Exception $e){
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
}
