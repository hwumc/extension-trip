{extends file="base.tpl"}
{block name="head"}
	<script src="{$cWebPath}/scripts/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		convert_urls : false,
		plugins: [
			"advlist autolink lists link image charmap preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste",
			"hr wordcount visualchars nonbreaking directionality textcolor"
		],
		removed_menuitems: 'newdocument',
		content_css: '{$cWebPath}/style/bootstrap-responsive.min.css',
	});
	</script>
{/block}
{block name="pagedescription"}{/block}
{block name="body"}
<script src="/scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="/scripts/bootstrap-datepicker.js" type="text/javascript"></script>

<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-create-header"}</legend>
	
	<div class="control-group">
		<label class="control-label" for="location">{message name="{$pageslug}-create-location"}</label>
		<div class="controls">
			<input class="input-xxlarge" type="text" id="location" name="location" placeholder="{message name="{$pageslug}-create-location-placeholder"}" required="true" value="{$location}" {if $allowEdit == "false"}disabled="true" {/if}/>
			<span class="help-inline">{message name="{$pageslug}-create-location-help"}</span>
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label" for="description">{message name="{$pageslug}-create-description"}</label>
		<div class="controls">
			<textarea class="input-xxlarge" type="text" id="description" name="description" placeholder="{message name="{$pageslug}-create-description-placeholder"}" {if $allowEdit == "false"}disabled="true" {/if}>{$description}</textarea>
			<span class="help-block">{message name="{$pageslug}-create-description-help"}</span>
		</div>
	</div>	
	
	<fieldset>
		<legend>{message name="{$pageslug}-create-tripdateheader"}</legend>
		
		<div class="control-group">
			<label class="control-label" for="year">{message name="{$pageslug}-create-uniweek"}</label>
			<div class="controls controls-row">
				<input class="span2" type="text" id="year" name="year" placeholder="{message name="{$pageslug}-create-year-placeholder"}" required="true" value="{$year}" {if $allowEdit == "false"}disabled="true" {/if}/>
				<input class="span2" type="text" id="semester" name="semester" placeholder="{message name="{$pageslug}-create-semester-placeholder"}" required="true" value="{$semester}" {if $allowEdit == "false"}disabled="true" {/if}/>
				<input class="span2" type="text" id="week" name="week" placeholder="{message name="{$pageslug}-create-week-placeholder"}" required="true" value="{$week}" {if $allowEdit == "false"}disabled="true" {/if}/>
			</div>
		</div>	

		<div class="control-group">
			<label class="control-label" for="startdate">{message name="{$pageslug}-create-startdate"}</label>
			<div class="controls">
				<input class="input-medium" type="text" id="startdate" placeholder="{message name="{$pageslug}-create-startdate-placeholder"}" data-date-format="dd/mm/yyyy" name="startdate" required="true" value="{$startdate}" {if $allowEdit == "false"}disabled="true" {/if} />
				 
				<span class="help-inline">{message name="{$pageslug}-create-startdate-help"}</span>
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label" for="enddate">{message name="{$pageslug}-create-enddate"}</label>
			<div class="controls">
				<input class="input-medium" type="text" id="enddate" name="enddate" placeholder="{message name="{$pageslug}-create-enddate-placeholder"}" data-date-format="dd/mm/yyyy"  required="true" value="{$enddate}" {if $allowEdit == "false"}disabled="true" {/if}/>
				<span class="help-inline">{message name="{$pageslug}-create-enddate-help"}</span>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>{message name="{$pageslug}-create-signupheader"}</legend>
		<div class="control-group">
			<label class="control-label" for="price">{message name="{$pageslug}-create-price"}</label>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on">£</span>
					<input class="input-medium" type="text" id="price" name="price" placeholder="{message name="{$pageslug}-create-price-placeholder"}" required="true" value="{$price}" {if 	$allowEdit == "false"}disabled="true" {/if}/>
				</div>
				<span class="help-inline">{message name="{$pageslug}-create-price-help"}</span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="spaces">{message name="{$pageslug}-create-spaces"}</label>
			<div class="controls">
				<input class="input-medium" type="text" id="spaces" name="spaces" placeholder="{message name="{$pageslug}-create-spaces-placeholder"}" required="true" value="{$spaces}" {if $allowEdit == "false"}disabled="true" {/if}/>
				<span class="help-inline">{message name="{$pageslug}-create-spaces-help"}</span>
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label" for="signupclose">{message name="{$pageslug}-create-signupclose"}</label>
			<div class="controls">
				<input class="input-medium" type="text" id="signupclose" name="signupclose" placeholder="{message name="{$pageslug}-create-signupclose-placeholder"}" data-date-format="dd/mm/yyyy" required="true" value="{$signupclose}" {if $allowEdit == "false"}disabled="true" {/if}/>
				<span class="help-inline">{message name="{$pageslug}-create-signupclose-help"}</span>
			</div>
		</div>	
	</fieldset>
	
	<div class="control-group">
		<div class="controls">
			<div class="btn-group">{if $allowEdit == "true"}<button type="submit" class="btn btn-primary">{message name="save"}</button>{/if}<a href="{$cScriptPath}/ManageTrips" class="btn">{message name="getmeoutofhere"}</a></div>
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