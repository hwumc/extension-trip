{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-trip-header"}</legend>
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			{include file="trips/tripitem.tpl" trip=$trip allowSignup=false}
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

		{if $showleavefrom == 1}
			<div class="control-group">
				<label class="control-label" for="leavefrom">{message name="{$pageslug}-leavefrom-label"}</label>
				<div class="controls">
					<div id="leaveFromPicker" class="input-append date">
						<input type="text" id="leavefrom" class="input-medium" value="{$leavefrom}" name="leavefrom" placeholder="{message name="{$pageslug}-create-leavefrom-placeholder"}" data-format="hh:mm" />
						<span class="add-on">
							<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
						</span>
					</div>
				</div>
			</div>
		{/if}

		{if $userisdriver == 1}
			<div class="control-group">
				<div class="controls">
					<label class="checkbox" for="driver">
						<input type="checkbox" id="driver" name="driver" {if $userisdriverexpired == 1}disabled{else}{$driver}{/if}/> {message name="{$pageslug}-driver-label"}
					</label>
					{if $userisdriverexpired == 1}
						<div class="alert alert-error alert-block">
							{message name="driverpermitexpired"}
						</div>
					{/if}
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

		{if $showPaymentMethods}
			<div class="control-group">
				<label class="control-label">{message name="{$pageslug}-signup-paymentmethods"}</label>
				<div class="controls">
					{foreach from=$allowedPaymentMethods key="method" item="methoddata"}
						<label class="radio">
							<input type="radio" name="paymentMethod" value="{$method}" {if $methoddata.check == "true"}checked="true" {/if} required/>
							{message name="paymentMethod-{$method}-userdescription"} (&pound;{$trip->getPrice()|escape}{if $methoddata.method->calculateHandlingCharge($trip->getPrice()) > 0} {message name="with-handling-charge"} &pound;{$methoddata.method->calculateHandlingCharge($trip->getPrice())|string_format:"%.2f"}{/if})
						</label>
					{/foreach}
				</div>
			</div>
		{/if}

		<p>{rawmessage name="{$pageslug}-signup-legalagreement-text"}</p>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" name="legalagreement" required="true"  {$legalagreementcheck}/>
					{message name="{$pageslug}-signup-legalagreement"}
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
{block name="scriptfooter"}
    <script type="text/javascript">
  $(function() {
    $('#leaveFromPicker').datetimepicker({
      language: 'en-GB',
	  pickDate: false,
	  pickSeconds: false
    });
  });
    </script>
{/block}