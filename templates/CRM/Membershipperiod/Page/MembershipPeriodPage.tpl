<h3 style="text-align:center;">Membership Periods</h3>

{* Example: Display a variable directly *}
<p style="text-align: center">{$membershipContent}</p>

{* testing api *}
<script>
    var contact_id = {$contact_id};
    console.log(contact_id);
    {literal}
        CRM.api3('MembershipPeriod', 'get', {contact_id:contact_id}).done(
            function(result) {
                console.log(result);
            });
    {/literal}
</script>
