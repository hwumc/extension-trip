{extends file="base.tpl"}
{block name="head"}
{include file="editorinit.tpl"}
{/block}
{block name="pagedescription"}{/block}
{block name="body"}

<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-create-header"}</legend>
	
	<div class="control-group">
		<label class="control-label" for="location">{message name="{$pageslug}-create-location"}</label>
		<div class="controls">
			<input class="input-xxlarge" type="text" id="location" name="location" placeholder="{message name="{$pageslug}-create-location-placeholder"}" required="true" value="{$location|escape}" {if $allowEdit == "false"}disabled="true" {/if}/>
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
				<div id="startDatePicker" class="input-append date">
					<input data-format="dd/MM/yyyy" type="text" id="startdate" placeholder="{message name="{$pageslug}-create-startdate-placeholder"}" class="input-medium" name="startdate" required="true" value="{$startdate}" {if $allowEdit == "false"}disabled="true" {/if}></input>
					<span class="add-on">
						<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
					</span>
				</div>
			</div>
		</div>	

		<div class="control-group">
			<label class="control-label" for="enddate">{message name="{$pageslug}-create-enddate"}</label>
			<div class="controls">
				<div id="endDatePicker" class="input-append date">
					<input class="input-medium" type="text" id="enddate" name="enddate" placeholder="{message name="{$pageslug}-create-enddate-placeholder"}" data-format="dd/MM/yyyy"  required="true" value="{$enddate}" {if $allowEdit == "false"}disabled="true" {/if}/>
					<span class="add-on">
						<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
					</span>
					<span class="help-inline">{message name="{$pageslug}-create-enddate-help"}</span>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>{message name="{$pageslug}-create-paymentheader"}</legend>
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
			<label class="control-label">{message name="{$pageslug}-create-paymentmethods"}</label>
			<div class="controls">
				{foreach from=$allowedPaymentMethods key="method" item="check"}
					<label class="checkbox">
						<input type="checkbox" name="pm-{$method}" {if $check == "true"}checked="true" {/if} {if $allowEdit == "false"}disabled="true" {/if}/>
						<strong>{message name="paymentMethod-{$method}"}</strong>: {message name="paymentMethod-{$method}-description"}
					</label>
				{/foreach}
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>{message name="{$pageslug}-create-signupheader"}</legend>
		<div class="control-group">
			<label class="control-label" for="spaces">{message name="{$pageslug}-create-spaces"}</label>
			<div class="controls">
				<input class="input-medium" type="text" id="spaces" name="spaces" placeholder="{message name="{$pageslug}-create-spaces-placeholder"}" required="true" value="{$spaces}" {if $allowEdit == "false"}disabled="true" {/if}/>
				<span class="help-inline">{message name="{$pageslug}-create-spaces-help"}</span>
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label" for="driverplaces">{message name="{$pageslug}-create-driverplaces"}</label>
			<div class="controls">
				<input class="input-medium" type="text" id="driverplaces" name="driverplaces" placeholder="{message name="{$pageslug}-create-driverplaces-placeholder"}" required="true" value="{$driverplaces}" {if $allowEdit == "false"}disabled="true" {/if}/>
				<span class="help-inline">{message name="{$pageslug}-create-driverplaces-help"}</span>
			</div>
		</div>	

		<div class="control-group">
			<label class="control-label" for="signupopen">{message name="{$pageslug}-create-signupopen"}</label>
			<div class="controls">
				<div id="signupOpenPicker" class="input-append date">
					<input class="input-medium" type="text" id="signupopen" name="signupopen" placeholder="{message name="{$pageslug}-create-signupopen-placeholder"}" data-format="dd/MM/yyyy hh:mm" value="{$signupopen}" {if $allowEdit == "false"}disabled="true" {/if}/>
					<span class="add-on">
						<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
					</span>
				</div>
				<span class="help-inline">{message name="{$pageslug}-create-signupopen-help"}</span>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="signupclose">{message name="{$pageslug}-create-signupclose"}</label>
			<div class="controls">
				<div id="signupClosePicker" class="input-append date">
					<input class="input-medium" type="text" id="signupclose" name="signupclose" placeholder="{message name="{$pageslug}-create-signupclose-placeholder"}" data-format="dd/MM/yyyy hh:mm"  required="true" value="{$signupclose}" {if $allowEdit == "false"}disabled="true" {/if}/>
					<span class="add-on">
						<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
					</span>
				</div>
				<span class="help-inline">{message name="{$pageslug}-create-signupclose-help"}</span>
			</div>
		</div>

		<div class="control-group">
			<div class="controls">
				<label for="hasmeal" class="checkbox">
					<input type="checkbox" id="hasmeal" name="hasmeal" {$hasmeal} {if $allowEdit == "false"}disabled="true" {/if}/> {message name="{$pageslug}-create-hasmeal"}
				</label>
			</div>
		</div>	

        <div class="control-group">
            <div class="controls">
                <label for="showleavefrom" class="checkbox">
                    <input type="checkbox" id="showleavefrom" name="showleavefrom" {$showleavefrom} {if $allowEdit == "false" }disabled="true" {/if}/> {message name="{$pageslug}-create-showleavefrom"}
                </label>
            </div>
        </div>	
	</fieldset>
	
	<div class="control-group">
		<div class="controls">
			<div class="btn-group">{if $allowEdit == "true"}<button type="submit" class="btn btn-primary">{message name="save"}</button>{/if}<a href="{$cScriptPath}/ManageTrips" class="btn">{message name="getmeoutofhere"}</a></div>
		</div>
	</div>
</form>
{/block}
{block name="scriptfooter"}
    <script type="text/javascript">
  $(function() {
    $('#startDatePicker').datetimepicker({
      language: 'en-GB',
	  pickTime: false
    });
  });

  $(function() {
    $('#endDatePicker').datetimepicker({
      language: 'en-GB',
	  pickTime: false
    });
  });
  
  $(function() {
    $('#signupClosePicker').datetimepicker({
      language: 'en-GB',
	  pickSeconds: false
    });
  });
  
  $(function() {
    $('#signupOpenPicker').datetimepicker({
      language: 'en-GB',
	  pickSeconds: false
    });
  });
    </script>
{/block}