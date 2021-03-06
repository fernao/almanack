{**
 * gridCell.tpl
 *
 * Copyright (c) 2009 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * a regular grid cell (with or without actions)
 *}
{assign var=cellId value="cell-`$id`"}
<td id="{$cellId}">
	{foreach name=actions from=$actions item=action}
		{include file="controllers/grid/gridAction.tpl" id="`$cellId`-action-`$action->getId()`" action=$action objectId=$cellId}
		{if $smarty.foreach.actions.last}
			&nbsp;&nbsp;&nbsp;
		{else}
			<br />
		{/if}
	{/foreach}
	{$label}
</td>
