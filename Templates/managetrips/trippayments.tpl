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
	<h3>{message name="{$pageslug}-payments-sheetheader"}</h3>
	
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<th>Place</th>
			<th>Person</th>
			<th>Method</th>
			<th>Status</th>
			<th>Amount</th>
			<th>Actions</th>
		</tr>
		{foreach from="$signups" item="s" key="tripid"}
			<tr {if $tripid >= $trip->getSpaces()}class="warning"{/if}{if $tripid < $trip->getDriverPlaces()}class="info"{/if}>
				<td>
					{if ! $s->getUserObject()->isAnonymous()}<a href="#" rel="tooltip" data-toggle="tooltip" title="{$s->getTime()}">{/if}
						{$tripid + 1}
					{if ! $s->getUserObject()->isAnonymous()}</a>{/if}
					{if $tripid >= $trip->getSpaces()}<a href="#" rel="tooltip" data-toggle="tooltip" title="{message name="Trips-signupsheet-waiting"}"><span class="label label-warning"><i class="icon-time icon-white"></i></span></a>{/if}
					{if $tripid < $trip->getDriverPlaces()}<a href="#" rel="tooltip" data-toggle="tooltip" title="{message name="Trips-signupsheet-driverplace"}"><span class="label label-info"><i class="icon-road icon-white"></i></span></a>{/if}
				</td>
				<td>{include file="userdisplay.tpl" user=$s->getUserObject()}{if $s->getUserObject()->isDriver()} <span class="label label-info"><i class="icon-road icon-white"></i></span>{/if}</td>
				<td>{$s->getPayment()->getMethodObject()->getName()}</td>
				<td>{message name="Payments-status-{$s->getPayment()->getStatus()}"}</td>
				<td>&pound;{$s->getPayment()->getAmount()|escape}</td>
				<td>
					<form method="post" action="{$cWebPath}/{$pageslug}/paymentWorkflow" style="margin-bottom:0px;">
						<input type="hidden" name="payment" value="{$s->getPayment()->getId()}" />
						<input type="hidden" name="trip" value="{$trip->getId()}" />
						<div class="btn-group">
							{foreach from=$s->getPayment()->getMethodObject()->getNextWorkflowState($s->getPayment()->getStatus()) item="action"}
								<button name="action" value="{$action}" class="btn btn-small {PaymentStatus::getButton($action)}"><i class="{PaymentStatus::getIcon($action)} icon-white"></i>&nbsp;{message name="paymentaction-to-{$action}"}</a>
							{/foreach}
						</div>
					</form>
				</td>
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