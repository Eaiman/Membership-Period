<?php

use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * FIXME - Add test description.
 *
 * Tips:
 *  - With HookInterface, you may implement CiviCRM hooks directly in the test class.
 *    Simply create corresponding functions (e.g. "hook_civicrm_post(...)" or similar).
 *  - With TransactionalInterface, any data changes made by setUp() or test****() functions will
 *    rollback automatically -- as long as you don't manipulate schema or truncate tables.
 *    If this test needs to manipulate schema or truncate tables, then either:
 *       a. Do all that using setupHeadless() and Civi\Test.
 *       b. Disable TransactionalInterface, and handle all setup/teardown yourself.
 *
 * @group headless
 * @author Eaiman Shoshi
 */
class CRM_Membershipperiod_MembershipPeriodApiTest extends CiviUnitTestCase implements HeadlessInterface {
	
	public function setUp() {
		parent::setUp();
		// FIXME: something NULLs $GLOBALS['_HTML_QuickForm_registered_rules'] when the tests are ran all together
		$GLOBALS['_HTML_QuickForm_registered_rules'] = array(
			'required' => array('html_quickform_rule_required', 'HTML/QuickForm/Rule/Required.php'),
			'maxlength' => array('html_quickform_rule_range', 'HTML/QuickForm/Rule/Range.php'),
			'minlength' => array('html_quickform_rule_range', 'HTML/QuickForm/Rule/Range.php'),
			'rangelength' => array('html_quickform_rule_range', 'HTML/QuickForm/Rule/Range.php'),
			'email' => array('html_quickform_rule_email', 'HTML/QuickForm/Rule/Email.php'),
			'regex' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
			'lettersonly' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
			'alphanumeric' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
			'numeric' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
			'nopunctuation' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
			'nonzero' => array('html_quickform_rule_regex', 'HTML/QuickForm/Rule/Regex.php'),
			'callback' => array('html_quickform_rule_callback', 'HTML/QuickForm/Rule/Callback.php'),
			'compare' => array('html_quickform_rule_compare', 'HTML/QuickForm/Rule/Compare.php'),
		);
		// change this to test DB
//		$GLOBALS['_CV']['TEST_DB_DSN'] = 'mysql://homestead:secret@localhost:3306/civicrm_tests_dev?new_link=true';
		
		$this->_contactID = $this->organizationCreate();
		$this->_membershipTypeID = $this->membershipTypeCreate(array('member_of_contact_id' => $this->_contactID));
		// add a random number to avoid silly conflicts with old data
		$this->_membershipStatusID = $this->membershipStatusCreate('test status' . rand(1, 1000));
		$params = array(
			'contact_id' => $this->_contactID,
			'currency' => 'USD',
			'financial_type_id' => 1,
			'contribution_status_id' => 1,
			'contribution_page_id' => NULL,
			'payment_instrument_id' => 1,
			'source' => 'STUDENT',
			'receive_date' => '20080522000000',
			'receipt_date' => '20080522000000',
			'total_amount' => 200.00,
			'trxn_id' => '22ereerwww322323',
			'invoice_id' => '22ed39c9e9ee6ef6031621ce0eafe6da70',
			'thankyou_date' => '20080522',
		);
		
		$this->_contribution = $this->callAPISuccess('contribution', 'create', $params);
	}
	
	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	public function tearDown() {
		$this->quickCleanup(array(
			'civicrm_membership',
			'civicrm_membership_payment',
			'civicrm_membership_log',
			'civicrm_membershipperiod',
			'civicrm_contribution'
		),
			TRUE
		);
		$this->membershipStatusDelete($this->_membershipStatusID);
		$this->membershipTypeDelete(array('id' => $this->_membershipTypeID));
		$this->contactDelete($this->_contactID);
	}
	
	/**
	 *  test with newly created membership and membership payment
	 */
	public function testCreate() {
		$contactId = $this->individualCreate();
		
		$params = array(
			'contact_id' => $contactId,
			'membership_type_id' => $this->_membershipTypeID,
			'join_date' => date('Ymd', strtotime('2006-01-21')),
			'start_date' => date('Ymd', strtotime('2006-01-21')),
			'end_date' => date('Ymd', strtotime('2006-12-21')),
			'source' => 'Payment',
			'is_override' => 1,
			'status_id' => $this->_membershipStatusID,
		);
		$membership = $this->callAPISuccess('membership', 'create', $params);
		
		$membershipId = $this->assertDBNotNull('CRM_Member_BAO_Membership', $contactId, 'id',
			'contact_id', 'Database check for created membership.'
		);
		
		$params = array(
			'contribution_id' => $this->_contribution['id'],
			'membership_id' => $membershipId,
		);
		$this->callAPIAndDocument('membership_payment', 'create', $params, __FUNCTION__, __FILE__);
		
		$result = $this->callAPISuccess("MembershipPeriod", "get", array("contact_id"=>$contactId));
		
		$this->assertEquals(1, $result["count"], 'Check a membership period is created or not');
//		$this->assertEquals($membership['values'][$membership['id']]['id'],
//			                  $result['values'][$result['id']]['membership_id'],
//			                  'Check membership_id');
//		$this->assertEquals($this->_contribution['id'],
//												$result["values"][$result['id']]["contribution_id"],
//												'Check contribution_id');
		foreach ($result['values'] as $v){
			$this->assertEquals($membership['values'][$membership['id']]['id'],
						$v["membership_id"],
						'Check membership_id');
			$this->assertEquals($this->_contribution['id'],
						$result["values"][$result['id']]["contribution_id"],
						'Check contribution_id');
		}
		
		$this->membershipDelete($membershipId);
		$this->contactDelete($contactId);
	}
	
	/**
	 * test with newly created membership when no payment is done,
	 * renew membership with change in membership type
	 * and membership payment
	 */
	public function testWhenRenewMembership() {
		$contactId = $this->individualCreate();
		$joinDate = $startDate = date("Ymd", strtotime(date("Ymd") . " -6 month"));
		$endDate = date("Ymd", strtotime($joinDate . " +1 year -1 day"));
		$params = array(
			'contact_id' => $contactId,
			'membership_type_id' => $this->_membershipTypeID,
			'join_date' => $joinDate,
			'start_date' => $startDate,
			'end_date' => $endDate,
			'source' => 'Payment',
			'is_override' => 1,
			'status_id' => $this->_membershipStatusID,
		);
		$membership = $this->callAPISuccess('membership', 'create', $params);
		$membershipId = $this->assertDBNotNull('CRM_Member_BAO_Membership', $contactId, 'id',
			'contact_id', 'Database check for created membership.'
		);
		
		$result = $this->callAPISuccess("MembershipPeriod", "get", array("contact_id"=>$contactId));
		$this->assertEquals(NULL,
					$result["values"][$result['id']]["contribution_id"],
					'Check contribution_id is null');
		
		$this->assertDBNotNull('CRM_Member_BAO_MembershipLog',
			$membership['values'][$membership['id']]['id'],
			'id',
			'membership_id',
			'Database checked on membershiplog record.'
		);
		
		// this is a test and we dont want qfKey generation / validation
		// easier to suppress it, than change core code
		$config = CRM_Core_Config::singleton();
		$config->keyDisable = TRUE;
		
		$isTestMembership = 0;
		list($MembershipRenew) = CRM_Member_BAO_Membership::processMembership(
			$contactId,
			$this->_membershipTypeID,
			$isTestMembership,
			NULL,
			NULL,
			NULL,
			1,
			FALSE,
			NULL,
			NULL,
			FALSE,
			NULL,
			NULL
		);
		
		$this->assertDBNotNull('CRM_Member_BAO_MembershipLog',
			$MembershipRenew->id,
			'id',
			'membership_id',
			'Database checked on membershiplog record.'
		);
		$dao = new CRM_Membershipperiod_DAO_MembershipPeriod();
		$dao->copyValues($membership['values'][$membership['id']]);
		$dao->find();
		CRM_Utils_Hook::post('edit', 'Membership', $MembershipRenew->id, $dao);
		
		$params = array(
			'contribution_id' => $this->_contribution['id'],
			'membership_id' => $MembershipRenew->id,
		);
		$this->callAPIAndDocument('membership_payment', 'create', $params, __FUNCTION__, __FILE__);
		
		$result = $this->callAPISuccess("MembershipPeriod", "get", array("contact_id"=>$contactId));
		
		$this->assertEquals(2, $result["count"],'Check two membership period is created or not');
		$counter = 0;
		foreach ($result['values'] as $k=>$v){
			$this->assertEquals($MembershipRenew->id,
						$v["membership_id"],
						'Check membership_id');
			
			if(!$v["contribution_id"]){
				// this one is for the first time membership when no payment was made
				$counter--;
			}else{
				// this one is for renewed membership when a payment was made
				$this->assertEquals($this->_contribution['id'],
							$v["contribution_id"],
							'Check contribution_id');
				$counter++;
			}
		}
		
		$this->assertEquals(0, $counter, 'contribution_id value is balanced');
		
		$this->membershipDelete($membershipId);
		$this->contactDelete($contactId);
	}
	
	
	/**
	 * The setupHeadless function runs at the start of each test case, right before
	 * the headless environment reboots.
	 *
	 * It should perform any necessary steps required for putting the database
	 * in a consistent baseline -- such as loading schema and extensions.
	 *
	 * The utility `\Civi\Test::headless()` provides a number of helper functions
	 * for managing this setup, and it includes optimizations to avoid redundant
	 * setup work.
	 *
	 * @see \Civi\Test
	 */
	public function setUpHeadless() {
		// TODO: Implement setUpHeadless() method.
		\Civi\Test::headless();
	}
}
