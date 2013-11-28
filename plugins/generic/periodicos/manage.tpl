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

<h3>{translate key="plugins.generic.periodicos.admin.manage_periodicos"}</h3>


<h5>{translate key="plugins.generic.periodicos.admin.upload_pdf_periodicos"}</h5>
<form method="post" action="{url op="manage" path="$issueId"}" enctype="multipart/form-data">

{if $pdfPeriodicos}<a href='{$baseUrl}/{$pdfPeriodicos}'>{translate key="plugins.generic.periodicos.admin.pdf_periodicos"}</a><br/>{/if}
<input type="file" name="pdf">
<input type="submit">
</form>

<hr>

<h5>{translate key="plugins.generic.periodicos.admin.manage_vincular"}</h5>
<select name="periodico_id" id="selectPeriodicos">
<option></option>
{html_options options=$listaPeriodicosFull}
</select>
<br/><br/>
<a href="{url page="periodicos" op="createPeriodico"}">{translate key="plugins.generic.periodicos.admin.criar_periodicos"}</a><br/>

<div id="">
{if $listaPeriodicosIssue}
<h5>Periódicos nessa edição:</h5>
<table id="lista_periodicos">
  <thead>
    <tr>
      <td>nome</td>
      <td>edições resenhadas</td>
      <td>editar periódico</td>
    </tr>
  </thead>
{foreach from=$listaPeriodicosIssue item=periodico key=periodicoId} 


{assign var=counter value=0}
{assign var=nome value=$periodico.nome}

{foreach from=$periodico key=periodicoEdicaoId item=edicao}
{if $counter==0}
  <td>{$nome} </td>
  <td>
{/if}
<!--<a href="{url page="periodicos" op="editPeriodicoEdicao" path="$periodicoId"}/{$issueId}">{translate key="plugins.generic.periodicos.admin.editar_periodicos"}</a> -->

{if $edicao!=$nome}
<a href='{url page="periodicos" op="editPeriodicoEdicao" path="$issueId"}/{$periodicoEdicaoId}'>{$edicao}</a> &nbsp; <span class="delete" id="{$periodicoEdicaoId}"> x </span>

<br/>
{/if}

{assign var=counter value=1}
{/foreach}
<a href='{url page="periodicos" op="editPeriodicoEdicao" path="$issueId"}/0/{$periodicoId}'>>>nova edição</a> &nbsp; 
<!-- editPeriodicoEdicao/11/0/42 -->

</td>
  <td><a href="{url page="periodicos" op="editPeriodico" path="$periodicoId"}">{translate key="plugins.generic.periodicos.admin.editar_periodicos"}</a> </td>
</tr>
{/foreach}
</table>

{else}
<h5>sem periodicos nessa seção.</h5>
{/if}
</div>
<br/>

{literal} 
<script type="text/javascript">
$(document).ready(function(){
  
  function apagar(periodicoEdicaoId) {
    textoDelete = "{/literal}{translate key="plugins.generic.periodicos.admin.editando.confirma_delete_periodicos_edicao"}{literal}";
    confirmacao = confirm(textoDelete);
    
    if (confirmacao) {
      window.location = '{/literal}{url page="periodicos" op="deletePeriodicoEdicao"}{literal}/' + periodicoEdicaoId;
    }
  }

  $('span.delete').click(
    function(id) {
      apagar($(this).attr('id'));
    }
  );

  // ao selecionar, envia para pagina de insercao de nova edicao
  $("select#selectPeriodicos").change(function(ev) {
    periodicoId = $(this).attr('value');
    issueId = '{/literal}{$issueId}{literal}';
    window.location='{/literal}{url page="periodicos" op="editPeriodicoEdicao"}{literal}/' + issueId + "/0/" + periodicoId; 
  });
});
</script>
{/literal}
