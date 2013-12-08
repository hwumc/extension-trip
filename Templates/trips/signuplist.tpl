{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}

<h2>{message name="{$pageslug}-signupsheet-header"}</h2>
{message name="{$pageslug}-signupsheet-body"}
<h3>{message name="{$pageslug}-signupsheet-table-header"}</h3>
<table class="table table-striped table-bordered table-hover">
<tr>
	<th>Place</th>
	<th>Person</th>
	<th>Signup Time</th>
	<th>Gear required</th>
	<th>Action Plan</th>
</tr>
{foreach from="$signups" item="s" key="tripid"}
<tr {if $tripid >= $trip->getSpaces()}class="warning"{/if}{if $tripid < $trip->getDriverPlaces()}class="info"{/if}>
	<td>{$tripid + 1}{if $tripid >= $trip->getSpaces()} {message name="{$pageslug}-signupsheet-waiting"}{/if}{if $tripid < $trip->getDriverPlaces()} {message name="Trips-signupsheet-driverplace"}{/if}</td>
	<td>{include file="userdisplay.tpl" user=$s->getUserObject()}{if $s->getUserObject()->isDriver()}<br /><span class="label label-info">{message name="Trips-signupsheet-driver"}</span>{/if}</td>
	<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getTime()}{/if}</td>
	<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getBorrowGear()|escape}</pre>{/if}</td>
	<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getActionPlan()|escape}</pre>{/if}</td>
</tr>
{/foreach}
</table>
{/block}