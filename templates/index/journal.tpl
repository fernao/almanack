{**
 * index.tpl
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Journal index page.
 *
 * $Id: journal.tpl,v 1.28.2.2 2009/05/22 20:10:03 mcrider Exp $
 *}
{assign var="pageTitleTranslated" value=$siteTitle}
{include file="common/header.tpl" hideFirst="1"}

<div>{$journalDescription}</div>

{call_hook name="Templates::Index::journal"}

{if $additionalHomeContent}
<br />
{$additionalHomeContent}
{/if}

<script type="text/javascript">
#window.location='http://www.almanack.unifesp.br/index.php/almanack/pages/view/neste_numero';
window.location='localhost/index.php/almanack/pages/view/neste_numero';

</script>

</div>

{include file="common/footer.tpl"}

