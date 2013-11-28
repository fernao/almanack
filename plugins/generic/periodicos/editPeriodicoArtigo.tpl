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

{assign var=periodico_artigo_id value=$periodicoArtigo.periodico_artigo_id}
{if $periodico_artigo_id != 0}
<h3>{translate key="plugins.generic.periodicos.admin.editando.artigo"}</h3>
{else}
<h3>{translate key="plugins.generic.periodicos.admin.inserindo.artigo"}</h3>
{/if}


<form name="issue" method="post" action="{url op="$actionForm" path="$periodico_artigo_id"}" enctype="multipart/form-data">

<table>
  <tr>
    <td width="20%" class="label">Periódico:</td>
    <td width="80%" class="value"><strong>{$periodico.nome}</strong></td>
  </tr>
  <tr>
    <td width="20%" class="label">Edição:</td>
    <td width="80%" class="value"><a href="{url op="editPeriodicoEdicao" path="$issueId"}/{$periodicoEdicao.periodico_edicao_id}">{$periodicoEdicao.edicao}</a></td>
  </tr>

  <tr>
    <td width="20%" class="label">Referência:</td>
    <td width="80%" class="value"><textarea name="referencia" id="referencia" cols="97" rows="2" class="textArea">{$periodicoArtigo.referencia}</textarea></td>
  </tr>

  <tr>
    <td width="20%" class="label">Resenha: </td>
    <td width="80%" class="value"><textarea name="abstract" id="abstract" cols="97" rows="25" class="textArea">{$article->getArticleAbstract()|strip_unsafe_html}</textarea></td>
  </tr>

    {assign var=palavraschave value=$article->getSubject($currentLocale)}
  <tr>
    <td width="20%" class="label">Palavras-chave:</td>
    <td width="80%" class="value"><input type="text" name="palavraschave" id="palavraschave" value="{$palavraschave}" size="99" class="textField" /></td>
  </tr>
  
    {assign var=keywords value=$article->getSubject('en_US')}
  <tr>
    <td width="20%" class="label">Keywords:</td>
    <td width="80%" class="value"><input type="text" name="keywords" id="keywords" value="{$keywords}" size="99" class="textField" /></td>
  </tr>


</table>
<input type="hidden" name="periodico_artigo_id" value="{$periodicoArtigo.periodico_artigo_id}">
<input type="hidden" name="periodico_edicao_id" value="{$periodicoEdicao.periodico_edicao_id}">
<input type="hidden" name="periodico_id" value="{$periodico.periodico_id}">
<input type="hidden" name="article_id" value="{$article->getArticleId()}">
{if $periodicoArtigo.issue_id != ''}
<input type="hidden" name="issue_id" value="{$periodicoArtigo.issue_id}">
{else}
<input type="hidden" name="issue_id" value="{$issueId}">
{/if}

<p><input type="submit" value="{translate key="common.save"}" class="button defaultButton" /> <input type="button" value="{translate key="common.cancel"}" onclick="document.location.href='{url op="index" escape=false}'" class="button" /></p>

</form>


