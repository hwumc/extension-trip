{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-trip-header"}</legend>
	<div class="control-group">
		<label class="control-label" for="location">{message name="{$pageslug}-signup-location"}</label>
		<div class="controls">
			<input class="input-xxlarge" type="text" id="location" value="{$location}" disabled="true"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="description">{message name="{$pageslug}-signup-description"}</label>
		<div class="controls">
			<textarea class="input-xxlarge" type="text" id="description" disabled>{$description}</textarea>
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label" for="year">{message name="{$pageslug}-signup-uniweek"}</label>
		<div class="controls controls-row">
			<input class="span2" type="text" id="year" value="{$year}" disabled="true"/>
			<input class="span2" type="text" id="semester" value="{$semester}" disabled="true"/>
			<input class="span2" type="text" id="week" value="{$week}" disabled="true"/>
		</div>
	</div>	

	<div class="control-group">
		<label class="control-label" for="startdate">{message name="{$pageslug}-signup-startenddate"}</label>
		<div class="controls controls-row">
			<input class="span2" type="text" id="startdate" data-date-format="dd/mm/yyyy" value="{$startdate}" disabled="true" />
			<input class="span2" type="text" id="enddate" data-date-format="dd/mm/yyyy" value="{$enddate}" disabled="true"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="signupclose">{message name="{$pageslug}-signup-signupclose"}</label>
		<div class="controls">
			<input class="input-medium" type="text" id="signupclose" data-date-format="dd/mm/yyyy" value="{$signupclose}" disabled="true"/>
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label" for="price">{message name="{$pageslug}-signup-price"}</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on">&pound;</span>
				<input class="input-medium" type="text" id="price" value="{$price}" disabled="true"/>
			</div>
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label" for="spaces">{message name="{$pageslug}-signup-spaces"}</label>
		<div class="controls">
			<input class="input-medium" type="text" id="spaces" value="{$spaces}" disabled="true"/>
		</div>
	</div>	

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
				<textarea rows="3" class="input-xxlarge" id="borrowgear" name="borrowgear" placeholder="{message name="{$pageslug}-borrowgear-placeholder"}" required="true" ></textarea>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="actionplan">{message name="{$pageslug}-actionplan-label"}</label>
			<div class="controls">
				<textarea rows="3" class="input-xxlarge" id="actionplan" name="actionplan" placeholder="{message name="{$pageslug}-actionplan-placeholder"}" required="true" ></textarea>
			</div>
		</div>

		<p>{message name="{$pageslug}-signup-confirm-text"}</p>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" name="confirm" value="confirmed" required="true" />
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