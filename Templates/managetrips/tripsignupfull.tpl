{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="pageheader"}{/block}
{block name="navbar"}{/block}
{block name="sidebar"}{/block}
{block name="styleoverride"}
	<style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 20px;
      }
    </style>
{/block}
{block name="rowinit"}<div class="span12">{/block}
{block name="body"}
<ul class="breadcrumb">
  <li><a href="{$cScriptPath}">Home</a> <span class="divider">/</span></li>
  <li><a href="{$cScriptPath}/ManageTrips">{message name="page-managetrips"}</a> <span class="divider">/</span></li>
  <li class="active">{message name="ManageTrips-button-signup"}</li>
</ul>
	<h3>{message name="{$pageslug}-signup-sheetheader"}</h3>
	<table class="table table-striped table-condensed">
		<tr>
			<th>Place</th>
			<th>Full Name<br />Mobile Phone</th>
			<th>Contact Name<br />Contact Phone</th>
			<th>Medical</th>
			<th>Gear</th>
			<th>Plan</th>
			<th>Experience</th>
			
			{if $trip->getHasMeal() == 1}
				<th>Meal?</th>
			{/if}
		</tr>
		{foreach from="$signups" item="s" key="tripid"}
			<tr>
				<td>{$tripid + 1}{if $tripid >= $trip->getSpaces()} {message name="Trips-signupsheet-waiting"}{/if}{if $tripid < $trip->getDriverPlaces()} {message name="Trips-signupsheet-driverplace"}{/if}</td>
				<td>{$s->getUserObject()->getFullName()|escape}{if ! $s->getUserObject()->isAnonymous()}<br />{$s->getUserObject()->getMobile()|escape}{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getUserObject()->getEmergencyContact()|escape}<br />{$s->getUserObject()->getEmergencyContactPhone()|escape}{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getUserObject()->getMedical()|escape}</pre>{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getBorrowGear()|escape}</pre>{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getActionPlan()|escape}</pre>{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getUserObject()->getExperience()|escape}</pre>{/if}</td>
				
				{if $trip->getHasMeal() == 1}
					<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getMealText()}{/if}</td>
				{/if}
			</tr>
		{/foreach}
	</table>
{/block}