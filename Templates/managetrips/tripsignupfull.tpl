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
			<th>Person</th>
			<th>Contact</th>
			<th>Medical</th>
			<th>Gear</th>
			<th>Plan</th>
			<th>Experience</th>
			
			{if $trip->getHasMeal() == 1}
				<th>Meal?</th>
			{/if}
		</tr>
		{foreach from="$signups" item="s" key="tripid"}
			<tr {if $tripid >= $trip->getSpaces()}class="warning"{/if}>
				<td>{$tripid + 1}{if $tripid >= $trip->getSpaces()} {message name="Trips-signupsheet-waiting"}{/if}</td>
				<td>{$s->getUserObject()->getFullName()}</td>
				<td>{$s->getUserObject()->getEmergencyContact()}<br />{$s->getUserObject()->getEmergencyContactPhone()}</td>
				<td><pre>{$s->getUserObject()->getMedical()}</pre></td>
				<td><pre>{$s->getBorrowGear()}</pre></td>
				<td><pre>{$s->getActionPlan()}</pre></td>
				<td><pre>{$s->getUserObject()->getExperience()}</pre></td>
				
				{if $trip->getHasMeal() == 1}
					<td>{$s->getMealText()}</td>
				{/if}
			</tr>
		{/foreach}
	</table>
{/block}