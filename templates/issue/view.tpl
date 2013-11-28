{**
 * view.tpl
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * View issue -- This displays the issue TOC or title page, as appropriate,
 * *without* header or footer HTML (see viewPage.tpl)
 *
 * $Id: view.tpl,v 1.32.2.1 2009/04/08 19:43:32 asmecher Exp $
 *}

<div id="cabecalho_corpo">
  <div id="titulo_cabecalho_corpo">{translate key="issue.toc"}
  </div>
  <div id="data_cabecalho_corpo">
    <h3>{$issue->getIssueIdentification()|strip_unsafe_html|nl2br}</h3>
  </div>
</div>


{if $subscriptionRequired && $showGalleyLinks && $showToc}
	<div id="accessKey">
		<img src="{$baseUrl}/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
		{translate key="reader.openAccess"}&nbsp;
		<img src="{$baseUrl}/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
		{if $purchaseArticleEnabled}
			{translate key="reader.subscriptionOrFeeAccess"}
		{else}
			{translate key="reader.subscriptionAccess"}
		{/if}
	</div>
{/if}
{if $issue}
	{if $issueId}
		{url|assign:"currentUrl" page="issue" op="view" path=$issueId|to_array:"showToc"}
	{else}
		{url|assign:"currentUrl" page="issue" op="current" path="showToc"}
	{/if}

	<div id="summary">
	{assign var=publishedArticlesTmp value=$publishedArticles}
	{foreach name=sectionsTmp from=$publishedArticlesTmp item=sectionTmp key=sectionId}
	{foreach from=$sectionTmp.articles item=articleTmp}
	{assign var=articlePath value=$articleTmp->getBestArticleId($currentJournal)}
	
	  <!-- link pdf completo -->
	{if $sectionTmp.title == "revista completa"}

	{foreach from=$articleTmp->getLocalizedGalleys() item=galley name=galleyList}
	<div id="download">
            <a href="{url page="article" op="download" path=$articlePath|to_array:$galley->getBestGalleyId($currentJournal)}" >{translate key="issue.downloadPdf"}</a>
	  
	</div>
	{/foreach}
	{/if}
	
	{/foreach}
	{/foreach}
	
	<!-- imagem da capa 
	{if $coverPagePath}<div id="issueCoverImage"><img src="{$coverPagePath|escape}med_{$issue->getFileName('pt_BR')|escape}"{if $coverPageAltText != ''} alt="{$coverPageAltText|escape}"{else} alt="{translate key="issue.coverPage.altText"}"{/if}{if $width} width="{$width|escape}"{/if}{if $height} height="{$height|escape}"{/if}/></div>{/if}
	<div id="issueCoverDescription">{$issue->getIssueCoverPageDescription()|strip_unsafe_html|nl2br}</div>
	<div id="issueDescription">{$issue->getIssueDescription()|strip_unsafe_html|nl2br}</div>
-->
	{include file="issue/issue.tpl"}
	</div>

{else}
	{translate key="current.noCurrentIssueDesc"}
{/if}


