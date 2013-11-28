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
{assign var="pageTitle" value="plugins.generic.periodicos"}
{include file="common/header.tpl"}

<p>{translate key="plugins.generic.periodicos.description"}</p>

{foreach from=$periodicos item=periodico}

	{assign var=articlePath value=$article->getBestArticleId($currentJournal)}


{include file="common/footer.tpl"}

