<?php

class CRM_Membershipperiod_Page_MembershipPeriodPage extends CRM_Core_Page {
	
	public function run() {
		// Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
		CRM_Utils_System::setTitle(ts('Membership Periods'));
		
		// These lines are for testing purpose
//		CRM_Core_Resources::singleton()
//			->addScriptFile('Membership Period', 'templates/CRM/membershipperiod/Page/MembershipPeriod.js', 2, 'html-header')
//			->addScriptFile('civicrm', 'templates/CRM/common/TabHeader.js', 1, 'html-header');
			
		
		$contact_id = -1;
		if (isset($_GET['contact_id'])) {
			/**
			 * Gets all the membership period records for the given "contact_id" and
			 * generates an HTML table
			 *
			 */
			$contact_id = $_GET['contact_id'];
			$params['contact_id'] = $contact_id;
			try {
				$result = civicrm_api3('MembershipPeriod', 'get', $params);
				if (!civicrm_error($result)
					&& isset($result['count']) && $result['count'] > 0
					&& isset($result['values']) && is_array($result['values'])){
					$records = $result['values'];
					$membershipContent = "<div>
							<table class=\"display\">
								<thead>
									<tr>
										<th>#</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Renewed on</th>
										<th>Contribution</th>
									</tr>
								</thead>
							";
					
					foreach ($records as $key => $record) {
						if($key % 2 == 0){
							$row_class = "even-row";
						} else {
							$row_class = "odd-row";
						}
						$membershipContent .= "<tr class=\"".$row_class."\">
									<td>" . ($key + 1) . "</td>
									<td>" . date('M j Y', strtotime($record['start_date'])) . "</td>
									<td>" . date('M j Y', strtotime($record['end_date'])) . "</td>
									<td>" . date('M j Y g:i A', strtotime($record['renewed_date'])) . "</td>
								";
						
						if ($record['contribution_id'] != NULL && $record['contribution_id'] != 0) {
							$membershipContent .= "	<td><a href=\"index.php?q=civicrm/contact/view/contribution&reset=1&id=" . $record['contribution_id'] . "&cid=$contact_id&action=view&context=contribution&selectedChild=contribute\">View</a></td>";
						} else {
							$membershipContent .= "<td>Not Paid</td>	";
						}
						$membershipContent .= "</tr>";
					}
					$membershipContent .= "</table></div>";
				} else {
					$membershipContent = "Sorry, No results found";
				}
			}catch (CiviCRM_API3_Exception $e) {
				$membershipContent = "Sorry, No results found";
			}
		} else {
			$membershipContent = "Sorry, No results found";
		}
		$this->assign('membershipContent', $membershipContent);
		
		// this line is for testing purpose for calling the api
		// through javascript
		$this->assign('contact_id', $contact_id);
		parent::run();
	}
	
}
