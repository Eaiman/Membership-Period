/**
 * Created by eaiman on 7/20/17.
 * @author Eaiman Shoshi
 */
CRM.$(function($) {
    // testing api
    var contact_id = $contact_id;
    console.log(contact_id);
    CRM.api3('MembershipPeriod', 'get', {contact_id: contact_id}).done(
        function (result) {
            console.log(result);
        });
})(CRM.$);
