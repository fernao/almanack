{**
 * index.tpl
 *
 * Copyright (c) 2010 Fernão Lopes
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Periodicos plugin index
 *
 * $Id
 *}
{include file="common/header.tpl"}

{if $action == 'criar'}
<h3>{translate key="plugins.generic.periodicos.admin.criar_periodicos"}</h3>
{else}
<h3>{translate key="plugins.generic.periodicos.admin.editar_periodicos"}</h3>
{/if}
{assign var=periodico_id value=$periodico.periodico_id}
{if $periodico_id}
<form name="issue" method="post" action="{url page="periodicos" op="$actionForm" path="$periodico_id"}" enctype="multipart/form-data">
{else}
<form name="issue" method="post" action="{url page="periodicos"  op="$actionForm" path="novo"}" enctype="multipart/form-data">
{/if}
<table>
  <tr>
    <td width="20%" class="label">{fieldLabel name="nome" key="nome"} Nome:</td>
    <td width="80%" class="value"><input type="text" name="nome" id="nome" value="{$periodico.nome|escape}" size="100" class="textField" /></td>
  </tr>

  <tr>
    <td width="20%" class="label">{fieldLabel name="local" key="local"} Local:</td>
    <td width="80%" class="value"><input type="text" name="local" id="local" value="{$periodico.local|escape}" size="70" class="textField" /></td>
  </tr>

  <tr>
    <td width="20%" class="label">{fieldLabel name="issn" key="issn"} Issn:</td>
    <td width="80%" class="value"><input type="text" name="issn" id="issn" value="{$periodico.issn|escape}" size="8" maxlength="10" class="textField" /></td>
  </tr>

  <tr>
    <td width="20%" class="label">{fieldLabel name="link" key="link"} Link:</td>
    <td width="80%" class="value"><input type="text" name="link" id="link" value="{$periodico.link|escape}" size="40" class="textField" /></td>
  </tr>

  <tr>
    <td width="20%" class="label">{fieldLabel name="acesso" key="acesso"} Acesso:</td>
    <td width="80%" class="value"><input type="text" name="acesso" id="acesso" value="{$periodico.acesso|escape}" size="40" class="textField" /></td>
    </td>
  </tr>

</table>
<input type="hidden" name="periodico_id" value="{$periodico.periodico_id}">
<input type="hidden" name="journal_id" value="{$periodico.journal_id}">
<input type="hidden" name="issue_id" value="{$issueId}">

<p><input type="submit" value="{translate key="common.save"}" class="button defaultButton" /> <input type="button" value="{translate key="common.cancel"}" onclick="document.location.href='{url op="index" escape=false}'" class="button" /></p>

</form>
