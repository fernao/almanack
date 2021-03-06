{** if the actOnId has not been specified, assume the id plays the role *}
{if !$actOnId} 
	{assign var=actOnId value=$id}
{/if}

{assign var=buttonId value="`$id`-`$action->getId()`-button"}

{if $action->getMode() eq $smarty.const.GRID_ACTION_MODE_MODAL}
	{modal url=$action->getUrl() actOnType=$action->getType() actOnId=$actOnId button="#`$buttonId`"}

{elseif $action->getMode() eq $smarty.const.GRID_ACTION_MODE_CONFIRM}
	{confirm url=$action->getUrl() dialogText=$action->getTitle() actOnType=$action->getType() actOnId=$actOnId button="#`$buttonId`"}

{/if}
<button type="button" id="{$buttonId}">{translate key=$action->getTitle()}</button>