<?php

class CRM_Membershipperiod_BAO_MembershipPeriod extends CRM_Membershipperiod_DAO_MembershipPeriod {

  /**
   * Create a new MembershipPeriod based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Membershipperiod_DAO_MembershipPeriod|NULL
   */
  public static function Create($params) {
  	print_r("2in");
    $className = 'CRM_Membershipperiod_DAO_MembershipPeriod';
    $entityName = 'MembershipPeriod';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }
  
	
	public static function getMembershipPeriod($params){
		$contact_id = $params['contact_id'];
		$sql = "SELECT mp.start_date, mp.end_date, mp.renewed_date, mp.contribution_id
				  FROM civicrm_membershipperiod mp
				  INNER JOIN civicrm_membership m ON m.id = mp.membership_id
			    WHERE m.contact_id = $contact_id";
	
		$results = CRM_Core_DAO::executeQuery($sql);
		$records = array();
		$i=0;
		while($results->fetch()){
			$records[$i]['start_date'] = $results->start_date;
			$records[$i]['end_date'] = $results->end_date;
			$records[$i]['renewed_date'] = $results->renewed_date;
			$records[$i]['contribution_id'] = $results->contribution_id;
			$i++;
		}
		
		return $records;
	}

}
