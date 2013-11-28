{**
 * footer.tpl
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site footer.
 *
 * $Id: footer.tpl,v 1.22.2.5 2009/04/08 19:43:31 asmecher Exp $
 *}
{if $displayCreativeCommons}
	{translate key="common.ccLicense"}
{/if}
{call_hook name="Templates::Common::Footer::PageFooter"}
</div><!-- content -->
</div><!-- main -->
</div><!-- body -->

<div id="footer">

      <!-- busca -->
      <div id="busca">
      <div id="lupa"></div>
      <div id="form_busca">
	<form method="post" action="{url page="search" op="results"}">
	  <input type="text" id="query" name="query" maxlength="255" value="" class="textField" />
	  <!-- valor padrao da busca - completa -->
	  <input type="hidden" name="searchField" size="1" value="128">
<!--	  <input type="submit" value="{translate key="common.search"}" class="button" />-->
	</form>
      </div>
      </div>
{if $pageTitle == 'common.openJournalSystems'}
  <div id="logos">  
      <ul>
    <li><a href="http://www.unifesp.br"><img src="{$publicFilesDir}/unifesp_neg.jpg"></a></li>
    <li><a href="http://www.usp.br"><img src="{$publicFilesDir}/usp-branco.png"></a></li>
    <li><a href="http://www.uff.br"><img src="{$publicFilesDir}/uff_fundopreto.png"></a></li>
    <li><a href="http://www.unirio.br"><img src="{$publicFilesDir}/unirio_fundopreto.png"></a></li>
    <li><a href="http://www.uerj.br"><img src="{$publicFilesDir}/uerj_fundopreto.jpg"></a></li>
    <li><a href="http://www.ufjf.br"><img src="{$publicFilesDir}/ufjf_fundopreto.png"></a></li>
    <li><a href="http://www.ufop.br"><img src="{$publicFilesDir}/logo_ufop.png"></a></li>
    <li><a href="http://www.ufrrj.br"><img src="{$publicFilesDir}/uffrj_fundopreto.jpg"></a></li>
    <li><a href="http://portal.ufes.br"><img src="{$publicFilesDir}/ufes_negativo.jpg"></a></li>
    <li><a href="http://www.brasiliana.usp.br"><img src="{$publicFilesDir}/logo_brasiliana.png"></a></li>
    <li><a href="http://www.ceo.historia.uff.br/index.php"><img src="{$publicFilesDir}/logo_pronex.png"></a></li>
    <li><a href="http://www.ceo.historia.uff.br/index.php"><img src="{$publicFilesDir}/logo_ceo.png"></a></li>
    <li><a href="http://www.faperj.br/"><img src="{$publicFilesDir}/logo_faperj.png"></a></li> 
    <li><a href="http://www.fapunifesp.edu.br"><img src="{$publicFilesDir}/logo_fap.jpg"></a></li> 
    <li><a href="http://www.latindex.unam.mx"><img src="{$publicFilesDir}/logo_latindex.jpg"></a></li> 
    <li><a href="http://www.doaj.org"><img src="{$publicFilesDir}/doaj-pq.png"></a></li> 
  </ul>

</div><!-- logos -->
{/if}

{get_debug_info}
{if $enableDebugStats}
	<div id="footerContent">
		<div class="debugStats">
		{translate key="debug.executionTime"}: {$debugExecutionTime|string_format:"%.4f"}s<br />
		{translate key="debug.databaseQueries"}: {$debugNumDatabaseQueries|escape}<br/>
		{translate key="debug.memoryUsage"}: {$debugMemoryUsage|escape}<br/>
		{if $debugNotes}
			<strong>{translate key="debug.notes"}</strong><br/>
			{foreach from=$debugNotes item=note}
				{translate key=$note[0] params=$note[1]}<br/>
			{/foreach}
		{/if}
		</div>
	</div><!-- footerContent -->
{/if}
</div><!-- footer -->

</div><!-- container -->
{if !empty($systemNotifications)}
	{translate|assign:"defaultTitleText" key="notification.notification"}
	<script type="text/javascript">
	<!--
	{foreach from=$systemNotifications item=notification}
		{literal}
			$.pnotify({
				pnotify_title: '{/literal}{if $notification->getIsLocalized()}{translate|escape:"js"|default:$defaultTitleText key=$notification->getTitle()}{else}{$notification->getTitle()|escape:"js"|default:$defaultTitleText}{/if}{literal}',
				pnotify_text: '{/literal}{if $notification->getIsLocalized()}{translate|escape:"js" key=$notification->getContents() param=$notification->getParam()}{else}{$notification->getContents()|escape:"js"}{/if}{literal}',
				pnotify_addclass: '{/literal}{$notification->getStyleClass()|escape:"js"}{literal}',
				pnotify_notice_icon: 'notifyIcon {/literal}{$notification->getIconClass()|escape:"js"}{literal}'
			});
		{/literal}
	{/foreach}
	// -->
	</script>
{/if}{* systemNotifications *}
</body>
</html>

