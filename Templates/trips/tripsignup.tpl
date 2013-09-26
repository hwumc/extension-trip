{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-trip-header"}</legend>
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<th rowspan="3">Week {$week|escape}, Semester {$semester|escape} {$year|escape}</th>
				<td colspan="2">{message name="{$pageslug}-text-price"}: &pound;{$price|escape}</td>
				<td>{message name="{$pageslug}-text-spaces"}: {$spaces|escape}</td>
				<td>{message name="{$pageslug}-text-startdate"}: {$startdate|escape}</td>
				<td>{message name="{$pageslug}-text-enddate"}: {$enddate|escape}</td>
			</tr>
			<tr>
				<td colspan="4">{$location|escape}</td>
				<td>{message name="{$pageslug}-text-registerby"}: {$signupclose|escape}</td>
			</tr>
			<tr>
				<td colspan="5">{$description}</td>
			</tr>
		</tbody>
	</table>

	<fieldset>
		<legend>{message name="{$pageslug}-user-header"}</legend>
		
		<div class="alert alert-block"><h4>{message name="{$pageslug}-user-alertheader"}</h4>{message name="{$pageslug}-user-alerttext"}</div>

		<div class="control-group">
			<label class="control-label" for="realname">{message name="EditProfile-realname-label"}</label>
			<div class="controls">
				<input type="text" id="realname" class="input-xlarge" value="{$realname}" disabled/>
			</div>
		</div>		
		
		<div class="control-group">
			<label class="control-label" for="mobile">{message name="EditProfile-mobile-label"}</label>
			<div class="controls">
				<input type="text" id="mobile" class="input-medium" value="{$mobile}" disabled/>
			</div>
		</div>		
		
		<div class="control-group">
			<label class="control-label" for="experience">{message name="EditProfile-experience-label"}</label>
			<div class="controls">
				<textarea rows="3" class="input-xxlarge" id="experience" disabled>{$experience}</textarea>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="medical">{message name="EditProfile-medical-label"}</label>
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" {$medicalcheck} disabled/>
					{message name="EditProfile-medicalcheck-label"}
				</label>
				<textarea class="input-xxlarge" rows="3" id="medical" disabled>{$medical}</textarea>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="contactname">{message name="EditProfile-contactname-label"}</label>
			<div class="controls">
				<input type="text" id="contactname" class="input-xlarge" value="{$contactname}" disabled/>
			</div>
		</div>		
		<div class="control-group">
			<label class="control-label" for="contactphone">{message name="EditProfile-contactphone-label"}</label>
			<div class="controls">
				<input type="text" id="contactphone" class="input-medium" value="{$contactphone}" disabled/>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>{message name="{$pageslug}-signup-header"}</legend>
		
		<div class="control-group">
			<label class="control-label" for="borrowgear">{message name="{$pageslug}-borrowgear-label"}</label>
			<div class="controls">
				<textarea rows="3" class="input-xxlarge" id="borrowgear" name="borrowgear" placeholder="{message name="{$pageslug}-borrowgear-placeholder"}" required="true" >{$borrowgear}</textarea>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="actionplan">{message name="{$pageslug}-actionplan-label"}</label>
			<div class="controls">
				<textarea rows="3" class="input-xxlarge" id="actionplan" name="actionplan" placeholder="{message name="{$pageslug}-actionplan-placeholder"}" required="true" >{$actionplan}</textarea>
			</div>
		</div>
		
		{if $hasmeal == 1}
			<div class="control-group">
				<div class="controls">
					<label class="checkbox" for="meal">
						<input type="checkbox" id="meal" name="meal" {$meal}/> {message name="{$pageslug}-meal-label"}
					</label>
				</div>
			</div>	
		{/if}

		<p>{message name="{$pageslug}-signup-confirm-text"}</p>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" name="confirm" value="confirmed" required="true" {$confirmcheck}/>
					{message name="{$pageslug}-signup-confirm"}
				</label>
			</div>
		</div>
	</fieldset>

	<div class="control-group">
		<div class="controls">
			<div class="btn-group"><button type="submit" class="btn btn-primary">{message name="{$pageslug}-button-signup"}</button><a href="{$cScriptPath}/Trips" class="btn">{message name="getmeoutofhere"}</a></div>
		</div>
	</div>
</form>

{/block}