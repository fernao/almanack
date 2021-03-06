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

<h3>{translate key="plugins.generic.periodicos.admin.edit_edicao_periodico"}</h3>

{assign var=periodico_edicao_id value=$periodico.periodico_edicao_id}
<form name="issue" method="post" action="{url op="$actionForm" path="$issueId"}/{$periodicoEdicaoId}/{$periodicoId}" enctype="multipart/form-data">
<h4>{$periodico.nome}</h4>

<h5>Resenhas relacionadas</h5>
{if $artigosPeriodicoEdicao[0].article_id}
<ul>
{foreach from=$artigosPeriodicoEdicao item=artigo}
  <li><a href="{url op="editPeriodicoArtigo"}/{$artigo.periodico_artigo_id}">{$artigo.referencia_artigo}</a> &nbsp;  <span class="delete" id="{$artigo.periodico_artigo_id}"> x </span>
</li>
{/foreach}
</ul>
{else}
Nenhuma resenha para essa edição.
{/if}
<a href="{url op="editPeriodicoArtigo"}/0/{$periodicoEdicao.periodico_edicao_id}/{$issueId}/{$periodicoEdicao.periodico_id}">adicionar nova resenha</a>
<br/><br/>

<table class="form_periodico">

  <tr>
    <td width="20%" class="label">{fieldLabel name="local" key="local"} {translate key="plugins.generic.periodicos.por_issue.referencia"}:</td>
    <td width="80%" class="value"><strong>{$periodico.nome|escape}</strong></td>
  </tr>

  <tr>
    <td width="20%" class="label">{fieldLabel name="nome" key="edicao"} Edição</td>
    <td width="80%" class="value"><input type="text" name="edicao" id="edicao" value="{$periodicoEdicao.edicao|escape}" size="100" class="textField" /></td>
  </tr>

  <tr>
    <td width="20%" class="label">{fieldLabel name="responsavel" key="responsavel"} {translate key="plugins.generic.periodicos.por_issue.responsavel"}:</td>
    <td width="80%" class="value">
      <select name="responsavel" id="responsavel" class="selectMenu">
	<option value=""></option>
	{html_options options=$listaResponsaveis selected=$periodicoEdicao.responsavel}
      </select>
    </td>
  </tr>

</table>
<input type="hidden" name="periodico_edicao_id" value="{$periodicoEdicao.periodico_edicao_id}">
<input type="hidden" name="periodico_id" value="{$periodicoEdicao.periodico_id}">
{if $periodicoEdicao.issue_id != ''}
<input type="hidden" name="issue_id" value="{$periodicoEdicao.issue_id}">
{else}
<input type="hidden" name="issue_id" value="{$issueId}">
{/if}

<p><input type="submit" value="{translate key="common.save"}" class="button defaultButton" /> <input type="button" value="{translate key="common.cancel"}" onclick="document.location.href='{url op="index" escape=false}'" class="button" /></p>

</form>
{literal} 
<script type="text/javascript">
$(document).ready(function(){
  
  function apagar(periodicoArtigoId) {
    textoDelete = "{/literal}{translate key="plugins.generic.periodicos.admin.editando.confirma_delete_periodicos_artigo"}{literal}";
    confirmacao = confirm(textoDelete);
    
    if (confirmacao) {
      window.location = '{/literal}{url page="periodicos" op="deletePeriodicoArtigo"}{literal}/' + periodicoArtigoId;
    }
  }

  $('span.delete').click(
    function(id) {
      apagar($(this).attr('id'));
    }
  );


});
</script>
{/literal}
