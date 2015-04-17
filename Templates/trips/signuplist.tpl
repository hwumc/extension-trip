{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}

<h2>{message name="{$pageslug}-signupsheet-header"}</h2>
{message name="{$pageslug}-signupsheet-body"}
<h3></h3>
<table class="table table-striped table-bordered table-hover">
	<tbody>
		{include file="trips/tripitem.tpl" trip=$trip allowSignup="false"}
	</tbody>
</table>
<h3>{message name="{$pageslug}-signupsheet-table-header"}</h3>
<table class="table table-striped table-bordered table-hover">
<tr>
	<th>Place</th>
	<th>Person</th>
	<th>Gear required</th>
	<th>Action Plan</th>
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
	<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getBorrowGear()|escape}{/if}</td>
	<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getActionPlan()|escape}{/if}</td>
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