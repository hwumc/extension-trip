{extends file="base.tpl"}
{block name="body"}
	<p>
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				{foreach from="$triplist" item="trip" key="tripid" }
					{include file="mytrips/item.tpl" trip=$trip->getTripObject() signup=$trip}
				{/foreach}
			</tbody>
		</table>
	</p>
{/block}