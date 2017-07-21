<?php

class CRM_Membershipperiod_BAO_MembershipPeriod extends CRM_Membershipperiod_DAO_MembershipPeriod {

  /**
   * Create a new MembershipPeriod based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Membershipperiod_DAO_MembershipPeriod|NULL
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
  
  public static function getvalues($params){
		try {
			$contact_id = $params['contact_id'];
			$sql = "SELECT mp.start_date, mp.end_date, mp.renewed_date, mp.contribution_id, mp.membership_id, mp.id
							FROM civicrm_membershipperiod as mp
							INNER JOIN civicrm_membership as m ON m.id = mp.membership_id
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
