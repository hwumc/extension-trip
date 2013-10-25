<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>HWUMC Trip Signup Sheet</title>
</head>
<body>	
	<h3>{message name="ManageTrips-signup-sheetheader"}</h3>
	<table class="table table-striped table-condensed">
		<tr>
			<th>Place</th>
			<th>Full Name<br />Mobile Phone</th>
			<th>Contact Name<br />Contact Phone</th>
			<th>Medical</th>
			<th>Gear</th>
			<th>Plan</th>
			<th>Experience</th>
			
			{if $trip->getHasMeal() == 1}
				<th>Meal?</th>
			{/if}
		</tr>
		{foreach from="$signups" item="s" key="tripid"}
			<tr {if $tripid >= $trip->getSpaces()}class="warning"{/if}{if $tripid < $trip->getDriverPlaces()}class="info"{/if}>
				<td>{$tripid + 1}{if $tripid >= $trip->getSpaces()} {message name="Trips-signupsheet-waiting"}{/if}{if $tripid < $trip->getDriverPlaces()} {message name="Trips-signupsheet-driverplace"}{/if}</td>
				<td>{$s->getUserObject()->getFullName()|escape}{if ! $s->getUserObject()->isAnonymous()}<br />{$s->getUserObject()->getMobile()|escape}{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getUserObject()->getEmergencyContact()|escape}<br />{$s->getUserObject()->getEmergencyContactPhone()|escape}{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getUserObject()->getMedical()|escape}</pre>{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getBorrowGear()|escape}</pre>{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getActionPlan()|escape}</pre>{/if}</td>
				<td>{if ! $s->getUserObject()->isAnonymous()}<pre>{$s->getUserObject()->getExperience()|escape}</pre>{/if}</td>
				
				{if $trip->getHasMeal() == 1}
					<td>{if ! $s->getUserObject()->isAnonymous()}{$s->getMealText()}{/if}</td>
				{/if}
			</tr>
		{/foreach}
	</table>
</body>
</html>