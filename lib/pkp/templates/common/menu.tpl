	<ul class="menu">
	  <!-- bloco 1 -->
		<li><a href="{url page="pages" op="view" path="apresentacao"}" target="{$target}">{translate key="navigation.about"}</a></li>
		<li>&nbsp;</li>

		<!-- bloco 2 -->
		{if $currentJournal}
			<li><a href="{url page="issue" op="current"}" target="{$target}">{translate key="navigation.current"}</a></li>
			<li><a href="{url page="issue" op="archive"}" target="{$target}">{translate key="navigation.archives"}</a></li>
		{/if}{* $currentJournal *}
			
		<li>&nbsp;</li>
		
		<!-- bloco 3 --> 
		<li><a href="{url page="pages" op="view" path="normas_publicacao"}" target="{$target}">{translate key="navigation.normas_publicacao"}</a></li>
		<li><a href="{url page="pages" op="view" path="envie_seu_texto"}" target="{$target}">{translate key="navigation.sendtext"}</a></li>
	        <li><a href="{url page="user"}" target="{$target}">{translate key="navigation.user"}</a></li>
		<li>&nbsp;</li>

		<!-- bloco 4 -->
		<li><a href="{url page="pages" op="view" path="expediente"}" target="{$target}">{translate key="navigation.expediente"}</a></li>
		<li><a href="{url page="about"}/contact" target="{$target}">{translate key="about.contact"}</a></li>
			
		{if $enableAnnouncements} <!-- noticias -->
			<li><a href="{url page="announcement"}" target="{$target}">{translate key="announcement.announcements"}</a></li>
		{/if}{* enableAnnouncements *}
			
		{call_hook name="Templates::Common::Header::Navbar::CurrentJournal"}

		{foreach from=$navMenuItems item=navItem}
			{if $navItem.url != '' && $navItem.name != ''}
				<li><a href="{if $navItem.isAbsolute}{$navItem.url|escape}{else}{$navItem.url|escape}{/if}" target="{$target}">{if $navItem.isLiteral}{$navItem.name|escape}{else}{translate key=$navItem.name}{/if}</a></li>
			{/if}
		{/foreach}
		<li>&nbsp;</li>
		<li>
		  {if $currentLocale == 'pt_BR'}
		  <a href="{url page="user" op="setLocale" path="en_US"}?source={$currentUrl|escape}" target="{$target}">english version</a>
		  {else if $locale == 'en_US' && $lingua=='English'}
		  <a href="{url page="user" op="setLocale" path="pt_BR"}?source={$currentUrl|escape}" target="{$target}">versão em português</a>
		  {/if}
		</li>
	</ul>
