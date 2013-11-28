{**
 * archive.tpl
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Issue Archive.
 *
 * $Id: archive.tpl,v 1.24.2.2 2009/05/22 20:10:03 mcrider Exp $
 *}
{assign var="pageTitle" value="archive.archives"}
{include file="common/header.tpl"}

<a name="issues"></a>

<div class="issues">

  <div id="almanack" style="height: 330px">
{iterate from=issues item=issue}
{assign var=thisYear value=$issue->getYear()}

  {if $issue->getIssueId() == 11}
  </div>
  <div id="almanack_brasiliense">
    <hr/>
    <h3>Almanack Brasiliense</h3>
  </div>
  {/if}
  
   <div class="issue">
	<div class="year title">
		<h3>{$issue->getYear()|escape}</h3>
	</div>
	
	{if $issue->getFileName('pt_BR') && $issue->getShowCoverPage('pt_BR') && !$issue->getHideCoverPageArchives('pt_BR')}

	<div class="issueCoverImage">
          <a href="{url op="view" path=$issue->getBestIssueId($currentJournal)}/showToc">
	    <img src="{$coverPagePath|escape}{$issue->getFileName('pt_BR')|escape}"{if $issue->getCoverPageAltText('pt_BR') != ''} alt="{$issue->getCoverPageAltText('pt_BR')|escape}"{else} alt="{translate key="issue.coverPage.altText"}"{/if}/></a>
	  </a>
	</div>
	<div class="almanack_titulo">almanack</div>
	<div>
	  <h5>
	    <a href="{url op="view" path=$issue->getBestIssueId($currentJournal)}/showToc">nº{$issue->getNumber()|strip_unsafe_html|nl2br} - {$issue->getYear()|strip_unsafe_html|nl2br}
	    </a>
	  </h5>
	</div>
	<div class="issueCoverDescription">{$issue->getIssueCoverPageDescription()|strip_unsafe_html|nl2br}</div>
	  
	{else}
	<h4><a href="{url op="view" path=$issue->getBestIssueId($currentJournal)}">{$issue->getIssueIdentification()|strip_unsafe_html|nl2br}</a></h4>
	<div class="issueDescription">{$issue->getIssueDescription()|strip_unsafe_html|nl2br}</div>
	{/if}
   </div>

{/iterate}
</div>

{if $notFirstYear}<br /></div>{/if}

<!--
<div id="issues_foot">
{if !$issues->wasEmpty()}
	{page_info iterator=$issues}&nbsp;&nbsp;&nbsp;&nbsp;
	{page_links anchor="issues" name="issues" iterator=$issues}
{else}
	{translate key="current.noCurrentIssueDesc"}
{/if}
</div>
-->

</div>
<div style="float: left; margin-top: 50px;">
<hr/>
{translate key="issue.oldNumbers"}
</div>

{include file="common/footer.tpl"}

