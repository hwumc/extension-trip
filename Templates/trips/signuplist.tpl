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
<tr {if $tripid >= $trip->getSpaces()}class="warning"{/if}>
	<td>{$tripid + 1}{if $tripid >= $trip->getSpaces()} {message name="{$pageslug}-signupsheet-waiting"}{/if}</td>
	<td>{$s->getUserObject()->getFullName()}</td>
	<td>{$s->getTime()}</td>
	<td><pre>{$s->getBorrowGear()}</pre></td>
	<td><pre>{$s->getActionPlan()}</pre></td>
</tr>
{/foreach}
</table>
{/block}