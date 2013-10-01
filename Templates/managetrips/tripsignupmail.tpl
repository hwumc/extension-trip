<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>HWUMC Trip Signup Sheet</title>
</head>
<body>	
	<h3>{message name="{$pageslug}-signup-sheetheader"}</h3>
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
			<tr {if $tripid >= $trip->getSpaces()}class="warning"{/if}>
				<td>{$tripid + 1}{if $tripid >= $trip->getSpaces()} {message name="Trips-signupsheet-waiting"}{/if}</td>
				<td>{$s->getUserObject()->getFullName()|escape}<br />{$s->getUserObject()->getMobile()|escape}</td>
				<td>{$s->getUserObject()->getEmergencyContact()|escape}<br />{$s->getUserObject()->getEmergencyContactPhone()|escape}</td>
				<td><pre>{$s->getUserObject()->getMedical()|escape}</pre></td>
				<td><pre>{$s->getBorrowGear()|escape}</pre></td>
				<td><pre>{$s->getActionPlan()|escape}</pre></td>
				<td><pre>{$s->getUserObject()->getExperience()|escape}</pre></td>
				
				{if $trip->getHasMeal() == 1}
					<td>{$s->getMealText()}</td>
				{/if}
			</tr>
		{/foreach}
	</table>
</body>
</html>