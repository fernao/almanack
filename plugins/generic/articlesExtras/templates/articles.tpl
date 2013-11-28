{**
 * articles.tpl
 *
 * Copyright (c) 2009 Richard González Alberto
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Articles list
 *
 *}
{assign var="pageTitle" value="plugins.generic.articlesExtras.selectArticle.name"}
{assign var="pageCrumbTitle" value="plugins.generic.articlesExtras.selectArticle.name"}
{include file="common/header.tpl"}

<br/>

<a name="issues"></a>

<table width="100%" class="listing">
	<tr>
		<td colspan="4" class="headseparator">&nbsp;</td>
	</tr>
	<tr class="heading" valign="bottom">
		<td width="65%">{translate key="article.title"}</td>
		<td width="5%" align="right">{translate key="common.action"}</td>
	</tr>
	<tr>
		<td colspan="4" class="headseparator">&nbsp;</td>
	</tr>
	
{foreach from=$articles item=article}
	<tr valign="top">
		<td><a href="{url page="article" op="view" path=$article->getArticleId()}" class="action">{$article->getArticleTitle()|escape}</a></td>
		<td align="right">
          {if $editType eq "body" || $editType eq "images" || $editType eq "citations"}
          	{if $editType eq "body"}{assign var="edit" value="Body"}{/if}
            {if $editType eq "images"}{assign var="edit" value="Images"}{/if}
            {if $editType eq "citations"}{assign var="edit" value="Citations"}{/if}
        	<a href="{url page="ArticlesExtrasPlugin" op="submit$edit" path=$article->getArticleId()}" class="action">{translate key="common.edit"}</a>
          {/if}
        </td>
	</tr>
	<tr>
		<td colspan="4" class="separator">&nbsp;</td>
	</tr>
{/foreach}
{if !$articles}
	<tr>
		<td colspan="4" class="nodata">{translate key="article.noArticles"}</td>
	</tr>
	<tr>
		<td colspan="4" class="endseparator">&nbsp;</td>
	</tr>
{/if}
</table>
{include file="common/footer.tpl"}
