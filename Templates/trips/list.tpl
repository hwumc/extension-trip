{extends file="base.tpl"}
{block name="body"}
	<p>
		<table class="table table table-striped table-bordered table-hover">
			<tbody>
				{foreach from="$triplist" item="trip" key="tripid" }
				<tr{if $trip->getStatus() == "open"} class="success"{/if}{if $trip->getStatus() == "cancelled"} class="error"{/if}>
					<th rowspan="3">Week {$trip->getWeek()|escape}, Semester {$trip->getSemester()|escape} {$trip->getYear()|escape}</th>
					<td colspan="2">{message name="{$pageslug}-text-price"}: &pound;{$trip->getPrice()|escape}</td>
					<td>{message name="{$pageslug}-text-spaces"}: {$trip->getSpaces()|escape}</td>
					<td>{message name="{$pageslug}-text-startdate"}: {$trip->getStartDate()|escape}</td>
					<td>{message name="{$pageslug}-text-enddate"}: {$trip->getEndDate()|escape}</td>
				</tr>
				<tr{if $trip->getStatus() == "open"} class="success"{/if}{if $trip->getStatus() == "cancelled"} class="error"{/if}>
					<td colspan="3">{$trip->getLocation()|escape}</td>
					<td>{message name="{$pageslug}-text-registerby"}: {$trip->getSignupClose()|escape}</td>
					<td>
						{if $allowSignup}
							{if $trip->getStatus() == "open"}
								<a href="{$cScriptPath}/{$pageslug}/signup/{$tripid}" class="btn btn-success">{message name="{$pageslug}-button-signup"}</a>
								<a href="{$cScriptPath}/{$pageslug}/list/{$tripid}" class="btn">{message name="{$pageslug}-button-viewlist"}</a>
							{else}
								{if $trip->getStatus() != "published"}
									<a href="{$cScriptPath}/{$pageslug}/list/{$tripid}" class="btn{if $trip->getStatus() == "cancelled"} btn-danger{/if}">{message name="{$pageslug}-tripstatusmessage-{$trip->getStatus()}"}{message name="{$pageslug}-button-signuplist"}</a>
								{else}
									{message name="{$pageslug}-tripstatusmessage-{$trip->getStatus()}"}
								{/if}
							{/if}
						{else}
							{message name="{$pageslug}-tripstatusmessage-{$trip->getStatus()}"}
						{/if}
					</td>
				</tr>
				<tr{if $trip->getStatus() == "open"} class="success"{/if}{if $trip->getStatus() == "cancelled"} class="error"{/if}>
					<td colspan="5">{$trip->getDescription()|escape}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</p>
{/block}