{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-workflow-header"}</legend>
	
	<div class="control-group">
		<label class="control-label" for="location">{message name="{$pageslug}-create-location"}</label>
		<div class="controls">
			<input class="input-xxlarge" type="text" id="location" name="location" placeholder="{message name="{$pageslug}-create-location-placeholder"}" required="true" value="{$location}" disabled="true"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="year">{message name="{$pageslug}-create-uniweek"}</label>
		<div class="controls controls-row">
			<input class="span2" type="text" id="year" name="year" placeholder="{message name="{$pageslug}-create-year-placeholder"}" required="true" value="{$year}" disabled="true"/>
			<input class="span2" type="text" id="semester" name="semester" placeholder="{message name="{$pageslug}-create-semester-placeholder"}" required="true" value="{$semester}" disabled="true"/>
			<input class="span2" type="text" id="week" name="week" placeholder="{message name="{$pageslug}-create-week-placeholder"}" required="true" value="{$week}" disabled="true"/>
		</div>
	</div>	

	<div class="control-group">
		<label class="control-label" for="startdate">{message name="{$pageslug}-workflow-startenddate"}</label>
		<div class="controls controls-row">
			<input class="span2" type="text" id="startdate" placeholder="{message name="{$pageslug}-create-startdate-placeholder"}" data-date-format="dd/mm/yyyy" name="startdate" required="true" value="{$startdate}" disabled="true" />
			<input class="span2" type="text" id="enddate" name="enddate" placeholder="{message name="{$pageslug}-create-enddate-placeholder"}" data-date-format="dd/mm/yyyy"  required="true" value="{$enddate}" disabled="true"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="signupclose">{message name="{$pageslug}-create-signupclose"}</label>
		<div class="controls">
			<input class="input-medium" type="text" id="signupclose" name="signupclose" placeholder="{message name="{$pageslug}-create-signupclose-placeholder"}" data-date-format="dd/mm/yyyy" required="true" value="{$signupclose}" disabled="true"/>
		</div>
	</div>	
	
	<fieldset>
		<legend>{message name="{$pageslug}-workflow-tripstatus"}</legend>

		<label class="radio">
			<input type="radio" name="status" value="new" {if $isnew}checked{else}{if !$cannew} disabled="true"{/if}{/if}>
			<strong>{message name="Trip-status-new"}</strong>: {message name="Trip-status-new-desc"}
		</label>

		<label class="radio">
			<input type="radio" name="status" value="published" {if $ispublished}checked{else}{if !$canpublished} disabled="true"{/if}{/if}>
			<strong>{message name="Trip-status-published"}</strong>: {message name="Trip-status-published-desc"}
		</label>

		<label class="radio">
			<input type="radio" name="status" value="open" {if $isopen}checked{else}{if !$canopen} disabled="true"{/if}{/if}>
			<strong>{message name="Trip-status-open"}</strong>: {message name="Trip-status-open-desc"}
		</label>

		<label class="radio">
			<input type="radio" name="status" value="closed" {if $isclosed}checked{else}{if !$canclosed} disabled="true"{/if}{/if}>
			<strong>{message name="Trip-status-closed"}</strong>: {message name="Trip-status-closed-desc"}
		</label>

		<label class="radio">
			<input type="radio" name="status" value="cancelled" {if $iscancelled}checked{else}{if !$cancancelled} disabled="true"{/if}{/if}>
			<strong>{message name="Trip-status-cancelled"}</strong>: {message name="Trip-status-cancelled-desc"}
		</label>

		<label class="radio">
			<input type="radio" name="status" value="completed" {if $iscompleted}checked{else}{if !$cancompleted} disabled="true"{/if}{/if}>
			<strong>{message name="Trip-status-completed"}</strong>: {message name="Trip-status-completed-desc"}
		</label>
		
		<label class="radio">
			<input type="radio" name="status" value="archived" {if $isarchived}checked{else}{if !$canarchived} disabled="true"{/if}{/if}>
			<strong>{message name="Trip-status-archived"}</strong>: {message name="Trip-status-archived-desc"}
		</label>

	</fieldset>

	<div class="control-group">
		<div class="controls">
			<div class="btn-group"><button type="submit" class="btn btn-primary">{message name="save"}</button><a href="{$cScriptPath}/ManageTrips" class="btn">{message name="getmeoutofhere"}</a></div>
		</div>
	</div>
</form>
<script>
$(function(){
	window.prettyPrint && prettyPrint();
	$('#startdate').datepicker();
	$('#enddate').datepicker();
	$('#signupclose').datepicker();
});
</script>
{/block}