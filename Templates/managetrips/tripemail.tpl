{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<p>{message name="{$pageslug}-email-selectdestinationuser"}</p>
	<div class="control-group">
		<div class="controls">
			{foreach from="$users" item="u"}
			<label class="radio">
				<input type="radio" name="user" id="userSelection" value="{$u->getId()}" />
				<tt>{$u->getUsername()|escape}</tt>:&nbsp;{$u->getFullName()|escape}
			</label>
			{/foreach}
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<div class="btn-group">
				<button type="submit" class="btn btn-primary">{message name="{$pageslug}-email-send"}</button><a href="{$cScriptPath}/{$pageslug}" class="btn">{message name="getmeoutofhere"}</a>
			</div>
		</div>
	</div>
</form>
{/block}