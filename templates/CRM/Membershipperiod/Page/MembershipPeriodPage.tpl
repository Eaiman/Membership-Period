{* @author Eaiman Shoshi *}
<h3 style="text-align:center;">Membership Periods</h3>

{* Example: Display a variable directly *}
<p style="text-align: center">{$membershipContent}</p>

{* testing api *}
{*<script>*}
    {*var contact_id = {$contact_id};*}
    {*console.log(contact_id);*}
    {*{literal}*}
    {*CRM.api3('MembershipPeriod', 'get', {contact_id:contact_id}).done(*}
        {*function(result) {*}
            {*console.log(result);*}
        {*});*}
    {*{/literal}*}
{*</script>*}

{*tried to added these line to civicrm core to check that when a membership created or*}
{*renewed the count of membership period is updated or not*}

{*{literal}*}
    {*<script type="text/javascript">*}
        {*CRM.$(function($) {*}
            {*// Changing relationships may affect related members and contributions. Ensure they are refreshed.*}
            {*$('.crm-member-membershiprenew-form-block').on('crmPopupFormSuccess crmFormSuccess', function() {*}
                {*CRM.alert("hello", "hi");*}
                {*CRM.tabHeader.resetTab('#tab_membershipperiod');*}
                {*CRM.tabHeader.updateCount('#tab_membershipperiod', 1*}
                    {*+ CRM.tabHeader.getCount('#tab_membershipperiod'));*}
            {*});*}
        {*});*}
    {*</script>*}
{*{/literal}*}



