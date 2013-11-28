{**
 * citation.tpl
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Article reading tools -- Capture Citation for ABNT
 *
 * $Id$
 *}
<div class="separator"></div>
<div id="citation">
{assign var=authors value=$article->getAuthors()}
{assign var=authorCount value=$authors|@count}
{foreach from=$authors item=author name=authors key=i}
	{assign var=firstName value=$author->getFirstName()}
	{$author->getLastName()|escape|upper}, {$firstName|escape}{/foreach}.
{$article->getLocalizedTitle()|strip_unsafe_html}.
<em>{$journal->getLocalizedTitle()|escape}</em>, {translate key="plugins.citationFormat.abnt.location"}, n.{$issue->getNumber()|escape},
{$article->getDatePublished()|date_format:'%b. %Y'|lower}. {translate key="plugins.citationFormats.abnt.retrieved" retrievedDate=$smarty.now|date_format:'%d %b. %Y' url=$articleUrl}.
</div>
