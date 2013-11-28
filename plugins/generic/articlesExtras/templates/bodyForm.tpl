{assign var="pageTitle" value="plugins.generic.articlesExtras.displayName"}
{include file="common/header.tpl"}

{translate key="plugins.generic.articlesExtras.form.introduction"}

<br />
<br />

<form id="formEdit" method="post" action="{url page="ArticlesExtrasPlugin" op="saveBody"}">

{include file="common/formErrors.tpl"}

<table id="settingsTable" border="0">
	<tr>
		<td>
			{translate key="plugins.generic.articlesExtras.form.body"}
		</td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="current" value="{$current}" />			
			<textarea name="body" cols="100" rows="30">
				{$currentBody|escape}
			</textarea>
		</td>
	</tr>
</table>

<p><input type="submit" value="{translate key="common.done"}" class="button" /></p>

</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>

{include file="common/footer.tpl"}
