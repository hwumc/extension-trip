﻿<tr{if $trip->getStatus() == "open"} class="success"{/if}{if $trip->getStatus() == "cancelled"} class="error"{/if}>
	<th rowspan="3">Week {$trip->getWeek()|escape}, Semester {$trip->getSemester()|escape} {$trip->getYearDisplay()|escape}</th>
	<td colspan="2">{message name="Trips-text-price"}: &pound;{$trip->getPrice()|escape}</td>
	<td>{message name="Trips-text-spaces"}: {$trip->getSpaces()|escape}</td>
	<td>{message name="Trips-text-startdate"}: {$trip->getStartDate()|escape}</td>
	<td>{message name="Trips-text-enddate"}: {$trip->getEndDate()|escape}</td>
</tr>
<tr{if $trip->getStatus() == "open"} class="success"{/if}{if $trip->getStatus() == "cancelled"} class="error"{/if}>
	<td colspan="3">{$trip->getLocation()|escape}</td>
	<td>{message name="Trips-text-registerby"}: {$trip->getSignupClose()|escape} UTC</td>
	<td>
		{if $allowViewList == "true"}
			{if $trip->getStatus() == "open"}
				{if $allowSignup == "true"}
					<a href="{$cScriptPath}/Trips/signup/{$trip->getId()}" {if $trip->isUserSignedUp( $currentUser->getId() ) !== false}class="btn btn-primary">{message name="Trips-button-editsignup"}{else}class="btn btn-success">{message name="Trips-button-signup"}{/if}</a>
				{/if}
				<a href="{$cScriptPath}/Trips/list/{$trip->getId()}" class="btn">{message name="Trips-button-viewlist"}</a>
			{else}
				{if $trip->getStatus() != "published"}
					<a href="{$cScriptPath}/Trips/list/{$trip->getId()}" class="btn{if $trip->getStatus() == "cancelled"} btn-danger{/if}">{message name="Trips-tripstatusmessage-{$trip->getStatus()}"}{message name="Trips-button-signuplist"}</a>
				{else}
					{if $trip->getSignupOpen() == ""}
						{message name="Trips-tripstatusmessage-{$trip->getStatus()}"}
					{else}
						{message name="Trips-text-registerstart"}: {$trip->getSignupOpen()|escape} UTC
					{/if}
				{/if}
			{/if}
		{else}
			{message name="Trips-tripstatusmessage-{$trip->getStatus()}"}
		{/if}
	</td>
</tr>
<tr{if $trip->getStatus() == "open"} class="success"{/if}{if $trip->getStatus() == "cancelled"} class="error"{/if}>
	<td colspan="5">{$trip->getDescription()}</td>
</tr>