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
	{if $allowPaymentInterface == true}
	<a href="{$cScriptPath}/{$pageslug}/payment/{$trip->getId()}" class="btn btn-small"><i class="icon-shopping-cart"></i>&nbsp;{message name="{$pageslug}-button-paymentsbutton"}</a>
	{/if}
	</div>
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<th>Place</th>
			<th>Person</th>
			<th>Gear required</th>
			<th>Action Plan</th>
			
			{if $trip->getShowLeaveFrom() == 1}
				<th>Leave from</th>
			{/if}

			{if $trip->getHasMeal() == 1}
				<th>Meal?</th>
			{/if}
		</tr>
		{foreach from="$signups" item="s" key="tripid"}
			<tr {if $tripid >= $trip->getSpaces()}class="warning"{/if}{if $tripid < $trip->getDriverPlaces()}class="info"{/if}>
				<td>
					{if ! $s->getUserObject()->isAnonymous()}<a href="#" rel="tooltip" data-toggle="tooltip" title="{$s->getTime()}">{/if}
						{$tripid + 1}
					{if ! $s->getUserObject()->isAnonymous()}</a>{/if}
					{if $tripid >= $trip->getSpaces()}<a href="#" rel="tooltip" data-toggle="tooltip" title="{message name="Trips-signupsheet-waiting"}"><span class="label label-warning"><i class="icon-time icon-white"></i></span></a>{/if}
					{if $tripid < $trip->getDriverPlaces()}<a href="#" rel="tooltip" data-toggle="tooltip" title="{message name="Trips-signupsheet-driverplace"}"><span class="label label-info"><i class="icon-road icon-white"></i></span></a>{/if}
					{if ! $s->getUserObject()->isAnonymous()}{if $s->canDelete()}<a href="{$cScriptPath}/ManageTrips/deletesignup/{$s->getId()}" class="btn btn-danger btn-mini"><i class="icon-trash icon-white"></i></a>{/if}{/if}
				</td>
				<td>{include file="userdisplay.tpl" user=$s->getUserObject()}{if $s->getUserObject()->isDriver()} <span class="label label-info"><i class="icon-road icon-white"></i></span>{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getBorrowGear()|escape}{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getActionPlan()|escape}{/if}</td>
							
				{if $trip->getShowLeaveFrom() == 1}
					<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getLeaveFrom()|escape}{/if}</td>
				{/if}
				
				{if $trip->getHasMeal() == 1}
					<td>{$s->getMealText()}</td>
				{/if}
			</tr>
		{/foreach}
		{foreach from="$deletedSignups" item="s" key="tripid"}
			<tr class="error">
				<td>
					<a href="#" rel="tooltip" data-toggle="tooltip" title="{$s->getTime()}">
						N/A
					</a>
					<a href="#" rel="tooltip" data-toggle="tooltip" title="{message name="Trips-signupsheet-deletedsignup"}"><span class="label"><i class="icon-trash icon-white"></i></span></a>
				</td>
				<td>{include file="userdisplay.tpl" user=$s->getUserObject()}{if $s->getUserObject()->isDriver()} <span class="label label-info"><i class="icon-road icon-white"></i></span>{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getBorrowGear()|escape}{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getActionPlan()|escape}{/if}</td>
							
				{if $trip->getShowLeaveFrom() == 1}
					<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getLeaveFrom()|escape}{/if}</td>
				{/if}
				
				{if $trip->getHasMeal() == 1}
					<td>{$s->getMealText()}</td>
				{/if}
			</tr>
		{/foreach}
	</table>
{/block}
{block name="scriptfooter"}
{* Initialise tooltips *}
<script type="text/javascript">
    $(function () {
    $("[rel='tooltip']").tooltip();
    });
  </script>
{/block}