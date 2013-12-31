{extends file="base.tpl"}
{block name="body"}
<div class="btn-group">
{if $allowCreate == "true"}
	<a href="{$cScriptPath}/{$pageslug}/create" class="btn btn-success">{message name="{$pageslug}-button-create"}</a>
{/if}
{if $archiveMode == "false"}
	<a href="{$cScriptPath}/{$pageslug}/archives" class="btn btn-info">{message name="{$pageslug}-button-viewarchive"}</a>
{else}
	<a href="{$cScriptPath}/{$pageslug}" class="btn btn-info">{message name="{$pageslug}-button-exitarchive"}</a>
{/if}
</div>
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
					{if $allowSignup == "true"}<th>{message name="{$pageslug}-text-signup"}</th>{/if}
					{if $allowDelete == "true"}<th>{message name="{$pageslug}-text-delete"}</th>{/if}
				</tr>
			</thead>
			<tbody>
				{foreach from="$triplist" item="trip" key="tripid" }
				<tr>
					<td>{$trip->getYearDisplay()|escape}</td>
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
					{if $allowSignup == "true"}
						<td>
							{if $trip->getStatus() != TripHardStatus::NEWTRIP && $trip->getStatus() != TripHardStatus::PUBLISHED }
								<a href="{$cScriptPath}/{$pageslug}/signup/{$tripid}" class="btn btn-small btn-info">{message name="{$pageslug}-button-signup"}</a>
							{/if}
						</td>
					{/if}
					{if $allowDelete == "true"}<td><a href="{if $trip->canDelete()}{$cScriptPath}/{$pageslug}/delete/{$tripid}{else}#{/if}" class="btn btn-small btn-danger {if !$trip->canDelete()}disabled{/if}">{message name="{$pageslug}-button-deletetrip"}</a></td>{/if}
				</tr>
				{/foreach}
			</tbody>
		</table>
	</p>
{/block}