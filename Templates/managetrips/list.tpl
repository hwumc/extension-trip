{extends file="base.tpl"}
{block name="body"}
{if $allowCreate == "true"}
	<p><a href="{$cScriptPath}/{$pageslug}/create" class="btn btn-success">{message name="{$pageslug}-button-create"}</a></p>
{/if}
	<p>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>{message name="{$pageslug}-text-year"}</th>
					<th>{message name="{$pageslug}-text-semester"}</th>
					<th>{message name="{$pageslug}-text-week"}</th>
					<th>{message name="{$pageslug}-text-location"}</th>
					<th>{message name="{$pageslug}-text-status"}</th>
					{if $allowEdit == "true"}<th>{message name="{$pageslug}-text-edit"}</th>{/if}
					{if $allowDelete == "true"}<th>{message name="{$pageslug}-text-delete"}</th>{/if}
				</tr>
			</thead>
			<tbody>
				{foreach from="$triplist" item="trip" key="tripid" }
				<tr>
					<td>{$trip->getYear()|escape}</td>
					<td>{message name="{$pageslug}-text-semester-short"}{$trip->getSemester()|escape}</td>
					<td>{message name="{$pageslug}-text-week-short"}{$trip->getWeek()|escape}</td>
					<th>{$trip->getLocation()|escape}</th>
					<td>
						{if $allowEdit == "true"}
							<a href="{$cScriptPath}/{$pageslug}/workflow/{$tripid}" class="btn btn-small">{$trip->getStatus()|escape} ({$trip->getRealStatus()|escape})</a>
						{else}
							{$trip->getStatus()|escape}
						{/if}
					</td>
					{if $allowEdit == "true"}<td><a href="{$cScriptPath}/{$pageslug}/edit/{$tripid}" class="btn btn-small btn-warning">{message name="{$pageslug}-button-editrip"}</a></td>{/if}
					{if $allowDelete == "true"}<td><a href="{if $trip->canDelete()}{$cScriptPath}/{$pageslug}/delete/{$tripid}{else}#{/if}" class="btn btn-small btn-danger {if !$trip->canDelete()}disabled{/if}">{message name="{$pageslug}-button-deletetrip"}</a></td>{/if}
				</tr>
				{/foreach}
			</tbody>
		</table>
	</p>
{/block}