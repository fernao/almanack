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

<!-- caixa de select -->
{*
<select>
  <option>{translate key="plugins.generic.periodicos.por_issue.outras_revistas"}</option>
  {foreach from=$outrosPeriodicos item=periodico}
  <option onClick="window.location='{$periodico.periodico_id}'">{$periodico.nome}</option>
  {/foreach}  
</select>
*}
<div id="periodicos_container_menu">
  <div id="baixar_periodicos">
{if $pdfPeriodicos}
    <p><a href='{$baseUrl}/{$pdfPeriodicos}'>{translate key="plugins.generic.periodicos.por_issue.link_pdf"}</a></p>
{/if}
  </div>
{* listagem dos periodicos dessa edicao *}
<div id="listagem_periodicos">

<h5>{translate key="plugins.generic.periodicos.por_issue.revistas_acompanhadas"}</h5>
<ul>
{foreach from=$outrosPeriodicos item=periodico}
 <li><a href='{$periodico.periodico_id}' alt="{$periodico.nome}">{$periodico.nome}</a></li>
{/foreach}
</ul>
</div>

</div>

<div id="periodicos_container_content">

<div id="caixa_periodico">

<div id="titulo_periodico">
<p><strong>{$periodicoAtual.nome}</strong></p>
  {foreach from=$edicoes item=edicao}
    <a href="#{$edicao}">{$edicao}</a><br/>
  {/foreach}
{$edicoesStr}
</div>

<p>{translate key="plugins.generic.periodicos.por_issue.responsavel"} <br/>
<strong>{$periodicoAtual.responsavel}</strong></p>

<p>{translate key="plugins.generic.periodicos.por_issue.referencia"}<br/>
<strong>{$periodicoAtual.local}</strong></p>

<p>{translate key="plugins.generic.periodicos.por_issue.issn"} <br/>
<strong>{$periodicoAtual.issn}</strong></p>

<p>

{translate key="plugins.generic.periodicos.por_issue.disponibilidade"}<br/>
{if $periodicoAtual.acesso == 'indisponível'}
<strong>{translate key="plugins.generic.periodicos.por_issue.indisponivel"}</strong>
{else}
<strong><a href='{$periodicoAtual.link}'>{$periodicoAtual.link}</a></strong> 
<br/>({$periodicoAtual.acesso})
{/if}
</p>

</div>

{foreach from=$periodicosArtigos item=artigos key=edicao}
  <div class="edicao_container">
    <div class="edicao_titulo">
      <h5><a name="{$edicao}">{$edicao}</a></h5>
    </div>
  {foreach from=$artigos item=artigo}
    {assign var=abstract value=$artigo.article->getAbstract($currentLocale)}
    {if $abstract == ''}
    {assign var=abstract value=$artigo.article->getAbstract('pt_BR')}
    {assign var=note value="show"}
    {else}
    {assign var=note value=""}
    {/if}
    
    {assign var=keywords value=$artigo.article->getSubject($currentLocale)}
    <div class="artigo">
  
    <h5>{$artigo.ref_artigo}</h5>

    {if $note == 'show'}
    <p><em>({translate key="plugins.generic.periodicos.por_issue.available_in_portuguese"})</em></p>
    {/if}
    <p>{$abstract}</p>
    <p><strong>{translate key="common.keywords"}:</strong> {$keywords}</p>
  </div>
{/foreach}
</div>
{/foreach}
</div>

{include file="common/footer.tpl"}

