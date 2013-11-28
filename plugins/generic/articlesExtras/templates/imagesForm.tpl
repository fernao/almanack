{assign var="pageTitle" value="plugins.generic.articlesExtras.editImages"}
{include file="common/header.tpl"}

{translate key="plugins.generic.articlesExtras.form.introduction"}

<br />
<br />
<table class="listing" width="100%">
	<tr>
    	<th colspan="3">Current Images</th>
    </tr>
    <tr class="heading">
        <th>No.</th>
        <th>Name</th>
        <th>Delete</th>
  	</tr>
    {assign var="counter" value=0}
    {foreach from=$images item=image}
    <tr>
        <td>{assign var="counter" value=$counter+1}{$counter}</td>
        <td>{$image->getName()}</td>
        <td><a href="{url page="ArticlesExtrasPlugin" op="submitImages" path=$current|to_array:"delete":$image->getFileId()}">Eliminar</a></td>
    </tr>
    {/foreach}
    {if !$images}
	<tr>
    	<td colspan="3"><center><i>No images found for this article!</i></center></td>
    </tr>
	{/if}
</table>
<br />
<br />

<form id="frmImage" name="image" enctype="multipart/form-data" method="post" action="{url page="ArticlesExtrasPlugin" op="saveImages"}">
<input type="hidden" name="current" value="{$current}" />
<table id="settingsTable" class="form_02" width="100%">
  <tr>
      <td>
        <h2>New image</h2>
      </td>
    </tr> 
    <tr width="100%">
    	<td>Imagen</td>
        <td><input type="file" id="filename" name="filename" /></td>
    </tr>
    <tr width="100%">
    	<td>Nombre</td>
        <td><input type="text" id="name" name="name" /></td>
    </tr>
    <tr width="100%">
    	<td>Description</td>
        <td><textarea id="description" name="description" rows="10" cols="40"></textarea></td>
    </tr>        
    <tr width="100%">
    	<td>
        	<input type="submit" class="button defaultButton" value="Add" />
        </td>
    </tr>
</table>
</form>

{include file="common/footer.tpl"}
