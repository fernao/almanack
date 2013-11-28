{**
 * issue.tpl
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Issue
 *
 * $Id: issue.tpl,v 1.42.2.3 2009/04/08 19:43:32 asmecher Exp $
 *} 
{foreach name=sections from=$publishedArticles item=section key=sectionId}

{if !$issueId || $issueId == ''}
{assign var=issueId value=$issue->getIssueId()}
{/if}

{if $section.title == "revista completa" || $section.title == "full journal"}

{continue}
{/if}
<div id="section-{$sectionId}" class="section separator">


<!-- periodicos  em revista -->

{if $section.title == "periódicos em revista" || $section.title == "journals in review"}

<a href="#"><h3 class="tocSectionTitle">{$section.title|escape}</h3></a>

<ul>
{foreach from=$periodicos item=periodico}
  <li><a href="{url page="periodicos" op="issue" path="$issueId"}/{$periodico.periodico_id}" class="file">{$periodico.nome}</a> ({$periodico.qtd})</li>
{/foreach}
</ul>
{continue}

{/if}


<!-- forum -->

{if $section.title == 'fórum' || $section.title == 'forum'}
<!-- secoes definidas hard nos locales -->
<h3 class="tocSectionTitle">{translate key="articles.section.article"} - {translate key="articles.section.forum"}</h3>


<!-- artigos -->

{elseif $section.title == 'artigos' || $section.title == 'articles'}
<h3 class="tocSectionTitle">{translate key="articles.section.article"}</h3>
{else}
{if $section.title}<h3 class="tocSectionTitle">{$section.title|escape}</h3>{/if}
{/if}

<!-- listagem dos artigos -->
  {foreach from=$section.articles item=article}
	{assign var=articlePath value=$article->getBestArticleId($currentJournal)}

{if $primeiro==false}
	{assign var=atual value=$section.title}
	{assign var=primeiro value=true}
{else}
  {if $section.title != $atual}
	{assign var=atual value=$section.title}
  {else}
 {/if}
{/if}


<table class="tocArticle" width="100%">
<tr valign="top">
	{if $article->getFileName($locale) && $article->getShowCoverPage($locale) && !$article->getHideCoverPageToc($locale)}
	<td rowspan="2">
	    <div class="tocArticleCoverImage">
		<a href="{url page="article" op="download" path=$articlePath}" class="file">
		<img src="{$coverPagePath|escape}{$article->getFileName($locale)|escape}"{if $article->getCoverPageAltText($locale) != ''} alt="{$article->getCoverPageAltText($locale)|escape}"{else} alt="{translate key="article.coverPage.altText"}"{/if}/></a></div>
	</td>
	{/if}
	{call_hook name="Templates::Issue::Issue::ArticleCoverImage"}

	{if $article->getArticleAbstract() == ""}
		{assign var=hasAbstract value=0}
	{else}
		{assign var=hasAbstract value=1}
	{/if}

	{assign var=articleId value=$article->getArticleId()}
	{if (!$subscriptionRequired || $article->getAccessStatus() || $subscribedUser || $subscribedDomain || ($subscriptionExpiryPartial && $articleExpiryPartial.$articleId))}
		{assign var=hasAccess value=1}
	{else}
		{assign var=hasAccess value=0}
	{/if}


<!-- resenhas --> 

	{if $section.title == "resenhas" || $section.title == "book reviews"}
	
	{foreach from=$article->getLocalizedGalleys() item=galley name=galleyList}
	<td class="tocTitle resenha">
	<a href="{url page="article" op="view" path=$articlePath|to_array:$galley->getBestGalleyId($currentJournal)}" class="file">{$article->getArticleTitle()}</a>
	  {if $subscriptionRequired && $showGalleyLinks && $restrictOnlyPdf}
	    {if $article->getAccessStatus() || !$galley->isPdfGalley()}	
	<img class="accessLogo" src="{$baseUrl}/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
	    {else}
	<img class="accessLogo" src="{$baseUrl}/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
	    {/if}
	  {/if}

	<!-- autor -->
	{foreach from=$article->getAuthors() item=author name=authorList}	
	{/foreach}
	
	    {if $article->getArticleAbstract() == ".<br />" || $article->getArticleAbstract() == "."}
	    {else}	    
	    {$article->getArticleAbstract()}
	  {/if}
	{translate key="resenha.by"} {$author->getFullName()}
	</td>
	{/foreach}
</tr>

<tr valign="top">

	
	<!-- RESENHAS FIM -->

	{else}
	
	<td class="tocTitle">{if !$hasAccess || $hasAbstract}<a href="{url page="article" op="view" path=$articlePath}">{$article->getArticleTitle()|strip_unsafe_html}</a>{else}{$article->getArticleTitle()|strip_unsafe_html}{/if}
          <span class="pdf">
		{if $hasAccess || ($subscriptionRequired && $showGalleyLinks)}
			{foreach from=$article->getLocalizedGalleys() item=galley name=galleyList}
				<a href="{url page="article" op="download" path=$articlePath|to_array:$galley->getBestGalleyId($currentJournal)}" class="file">{$galley->getGalleyLabel()|escape}</a>
				{if $subscriptionRequired && $showGalleyLinks && $restrictOnlyPdf}
					{if $article->getAccessStatus() || !$galley->isPdfGalley()}	
						<img class="accessLogo" src="{$baseUrl}/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
					{else}
						<img class="accessLogo" src="{$baseUrl}/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
					{/if}
				{/if}
			{/foreach}
			{if $subscriptionRequired && $showGalleyLinks && !$restrictOnlyPdf}
				{if $article->getAccessStatus()}
					<img class="accessLogo" src="{$baseUrl}/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
				{else}
					<img class="accessLogo" src="{$baseUrl}/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
				{/if}
			{/if}				
		{/if}
        </span>
	</td>
	
	{/if}
</tr>

<tr valign="top">
  
	{if $section.title == "resenhas" || $section.title == "book reviews"}
	
	{else}
	<td class="tocAuthors">
		{if (!$section.hideAuthor && $article->getHideAuthor() == 0) || $article->getHideAuthor() == 2}
			{foreach from=$article->getAuthors() item=author name=authorList}
				{$author->getFullName()|escape}{if !$smarty.foreach.authorList.last},{/if}
			{/foreach}
		{else}
			&nbsp;
		{/if}
	</td>
	{/if}
	<td class="tocPages">{$article->getPages()|escape}</td>
</tr>
</table>
{/foreach}

</div>

{if !$smareach.sections.last}

{/if}
{/foreach}

