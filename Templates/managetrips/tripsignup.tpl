{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
	<h3>{message name="{$pageslug}-signup-tripheader"}</h3>
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr{if $trip->getStatus() == "open"} class="success"{/if}{if $trip->getStatus() == "cancelled"} class="error"{/if}>
				<th rowspan="3">Week {$trip->getWeek()|escape}, Semester {$trip->getSemester()|escape} {$trip->getYear()|escape}</th>
				<td colspan="2">{message name="Trips-text-price"}: &pound;{$trip->getPrice()|escape}</td>
				<td>{message name="Trips-text-spaces"}: {$trip->getSpaces()|escape}</td>
				<td>{message name="Trips-text-startdate"}: {$trip->getStartDate()|escape}</td>
				<td>{message name="Trips-text-enddate"}: {$trip->getEndDate()|escape}</td>
			</tr>
			<tr{if $trip->getStatus() == "open"} class="success"{/if}{if $trip->getStatus() == "cancelled"} class="error"{/if}>
				<td colspan="4">{$trip->getLocation()|escape}</td>
				<td>{message name="Trips-text-registerby"}: {$trip->getSignupClose()|escape}</td>
			</tr>
			<tr{if $trip->getStatus() == "open"} class="success"{/if}{if $trip->getStatus() == "cancelled"} class="error"{/if}>
				<td colspan="5">{$trip->getDescription()}</td>
			</tr>
		</tbody>
	</table>
	<h3>{message name="{$pageslug}-signup-sheetheader"}</h3>
	<div class="btn-group" style="margin-bottom:15px;">
	<a href="{$cScriptPath}/{$pageslug}/signupfull/{$trip->getId()}" class="btn btn-small btn-info"><i class="icon-print icon-white"></i>&nbsp;{message name="{$pageslug}-button-fullsheet-print"}</a>
	<a href="{$cScriptPath}/{$pageslug}/signupemail/{$trip->getId()}" class="btn btn-small"><i class="icon-envelope"></i>&nbsp;{message name="{$pageslug}-button-fullsheet-email"}</a>
	</div>
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<th>Place</th>
			<th></th>
			<th>Person</th>
			<th>Signup Time</th>
			<th>Gear required</th>
			<th>Action Plan</th>
			{if $trip->getHasMeal() == 1}
				<th>Meal?</th>
			{/if}
		</tr>
		{foreach from="$signups" item="s" key="tripid"}
			<tr {if $tripid >= $trip->getSpaces()}class="warning"{/if}{if $tripid < $trip->getDriverPlaces()}class="info"{/if}>
				<td>{$tripid + 1}{if $tripid >= $trip->getSpaces()} {message name="Trips-signupsheet-waiting"}{/if}{if $tripid < $trip->getDriverPlaces()} {message name="Trips-signupsheet-driverplace"}{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<a href="{$cScriptPath}/ManageTrips/deletesignup/{$s->getId()}" class="btn btn-danger"><i class="icon-trash icon-white"></i>&nbsp;{message name="{$pageslug}-signupsheet-deletebutton"}</a>{/if}</td>
				<td>{$s->getUserObject()->getFullName()|escape}<br />{if $s->getUserObject()->isDriver()}<span class="label label-info">{message name="Trips-signupsheet-driver"}</span>{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getTime()}{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getBorrowGear()|escape}</pre>{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getActionPlan()|escape}</pre>{/if}</td>
				
				{if $trip->getHasMeal() == 1}
					<td>{$s->getMealText()}</td>
				{/if}
			</tr>
		{/foreach}
	</table>

{/block}