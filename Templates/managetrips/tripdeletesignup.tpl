{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-deletesignup-header"}</legend>
	
	<p>{message name="{$pageslug}-deletesignup-areyousure"}</p>

	<div class="control-group">
		<div class="controls">
			<label class="checkbox">
				<input type="checkbox" name="confirm" value="confirmed" required="true" />
				{message name="{$pageslug}-confirm-deletesignup"}
			</label>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<div class="btn-group">
				<button type="submit" class="btn btn-danger">{message name="delete"}</button><a href="{$cScriptPath}/{$pageslug}" class="btn">{message name="getmeoutofhere"}</a>
			</div>
		</div>
	</div>
</form>

{/block}