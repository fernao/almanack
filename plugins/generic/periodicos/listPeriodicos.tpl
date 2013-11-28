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

{literal} 
<script type="text/javascript">
$(document).ready(function(){

  function apagar(periodicoId) {
    textoDelete = "{/literal}{translate key="plugins.generic.periodicos.admin.editando.confirma_delete_periodicos"}{literal}";
    confirmacao = confirm(textoDelete);
    
    if (confirmacao) {
      window.location = '{/literal}{url page="periodicos" op="deletePeriodico"}{literal}/' + periodicoId;
    }
  }

  $('.delete p').click(
    function(id) {
      apagar($(this).attr('id'));
    }
  );

});
</script>
{/literal} 


<h3>{translate key="plugins.generic.periodicos.admin.listar_periodicos"}</h3>

<a href="{url page="periodicos" op="createPeriodico"}">incluir novo periodico</a>
{if $periodicos} 

<table id="lista_periodicos">
  <thead>
    <tr>
      <td><a href="{url page="periodicos" op="listarPeriodicos" path="nome"}">Nome</a></td>
      <td><a href="{url page="periodicos" op="listarPeriodicos" path="local"}">Local</a></td>
      <td><a href="{url page="periodicos" op="listarPeriodicos" path="link"}">Link</a></td>
      <td>Apagar</td>
    </td>
  </thead>
{foreach from=$periodicos item=periodico}
{assign var=periodicoId value=$periodico.periodico_id}
  <tr>
    <td><a href="{url op="editPeriodico" path="$periodicoId"}" class="title">{$periodico.nome}</a></td>
    <td>{$periodico.local}</td>
    <td>{if $periodico.link != ''}<a href="{$periodico.link}" target="_blank">link</a>{/if}</td>
    <td class="delete"><p id="{$periodicoId}">x</p></td>
  </tr>
{/foreach}
</table>
{/if}
