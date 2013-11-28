{**
 * header.tpl
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site header.
 *
 * $Id: header.tpl,v 1.59.2.2 2009/04/08 19:43:31 asmecher Exp $
 *}
{if !$pageTitleTranslated}{translate|assign:"pageTitleTranslated" key=$pageTitle}{/if}
{if $pageCrumbTitle}{translate|assign:"pageCrumbTitleTranslated" key=$pageCrumbTitle}{elseif !$pageCrumbTitleTranslated}{assign var="pageCrumbTitleTranslated" value=$pageTitleTranslated}{/if}
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$defaultCharset|escape}" />
	<title>{$pageTitleTranslated}</title>
	<meta name="description" content="{$metaSearchDescription|escape}" />
	<meta name="keywords" content="{$metaSearchKeywords|escape}" />
	<meta name="generator" content="{translate key="common.openJournalSystems"} {$currentVersionString|escape}" />

	<!-- Mimic Internet Explorer 7 -->
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" >
	{$metaCustomHeaders}
 	{if $displayFavicon}<link rel="icon" href="{$faviconDir}/{$displayFavicon.uploadName|escape:"url"}" />{/if}
	<link rel="stylesheet" href="{$baseUrl}/lib/pkp/styles/pkp.css" type="text/css" />
	<link rel="stylesheet" href="{$baseUrl}/styles/common.css" type="text/css" />
        <link rel="shortcut icon" href="{$baseUrl}/favicon.ico" type="image/vnd.microsoft.icon"> 
	
	<!-- Base Jquery -->
	{if $allowCDN}<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">{literal}
		// Provide a local fallback if the CDN cannot be reached
		if (typeof google == 'undefined') {
			document.write(unescape("%3Cscript src='{/literal}{$baseUrl}{literal}/lib/pkp/js/lib/jquery/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
			document.write(unescape("%3Cscript src='{/literal}{$baseUrl}{literal}/lib/pkp/js/lib/jquery/plugins/jqueryUi.min.js' type='text/javascript'%3E%3C/script%3E"));
		} else {
			google.load("jquery", "{/literal}{$smarty.const.CDN_JQUERY_VERSION}{literal}");
			google.load("jqueryui", "{/literal}{$smarty.const.CDN_JQUERY_UI_VERSION}{literal}");
		}
	{/literal}</script>
	{else}
	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/lib/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/lib/jquery/plugins/jqueryUi.min.js"></script>
	{/if}

	{call_hook|assign:"leftSidebarCode" name="Templates::Common::LeftSidebar"}
	{call_hook|assign:"rightSidebarCode" name="Templates::Common::RightSidebar"}
	{if $leftSidebarCode || $rightSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/sidebar.css" type="text/css" />{/if}
	{if $leftSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/leftSidebar.css" type="text/css" />{/if}
	{if $rightSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/rightSidebar.css" type="text/css" />{/if}
	{if $leftSidebarCode && $rightSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/bothSidebars.css" type="text/css" />{/if}

	{foreach from=$stylesheets item=cssUrl}
		<link rel="stylesheet" href="{$cssUrl}" type="text/css" />
	{/foreach}

	<!--[if IE]>
	<link rel="stylesheet" href="{$baseUrl}/plugins/themes/almanackBlack/ie.css" type="text/css" />
	<![endif]-->
	<!--[if IE 6]>
	<link rel="stylesheet" href="{$baseUrl}/plugins/themes/almanackBlack/ie6.css" type="text/css" />
	<script type="text/javascript" src="{$baseUrl}/plugins/themes/almanackBlack/supersleight-min.js"></script>
	<![endif]-->
	
	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/general.js"></script>
	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/tag-it.js"></script>
	<!-- Add javascript required for font sizer -->
	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/fontController.js" ></script>
	<script type="text/javascript">{literal}
		$(function(){
			fontSize("#sizer", "body", 9, 16, 32, "{/literal}{$basePath|escape:"javascript"}{literal}"); // Initialize the font sizer
		});
	{/literal}</script>

	<script type="text/javascript">
        // initialise plugins
		{literal}
        $(function(){
        	{/literal}{if $validateId}{literal}
			jqueryValidatorI18n("{/literal}{$baseUrl}{literal}", "{/literal}{$currentLocale}{literal}"); // include the appropriate validation localization
			e("form[name={/literal}{$validateId}{literal}]").validate({
				errorClass: "error",
				highlight: function(element, errorClass) {
					$(element).parent().parent().addClass(errorClass);
				},
				unhighlight: function(element, errorClass) {
					$(element).parent().parent().removeClass(errorClass);
				}
			});
			{/literal}{/if}{literal}
		});
		{/literal}
	</script>
	{$additionalHeadData}
	
</head>
{if $title == 'archive.archives'}{assign var="title" value="archives"}{/if}
<body class="{$requestedPage|lower} {$title|regex_replace:"[\h]":"_"|lower} {$currentLocale}">
<div id="container">
  <div id="header">
    <div id="headerTitle">
      <!-- almanack braziliense -->
      <div id="logo_texto">
	<a href="{url page="index"}">

	<img src="{$publicFilesDir}/logo_preto.png" {if $displayPageHeaderTitle.altText != ''}alt="{$displayPageHeaderTitle.altText|escape}"{else}alt="{translate key="common.pageHeader.altText"}"{/if} />
	</a>
	<!-- revista eletronica -->

	<img src="{$publicFilesDir}/{$displayPageHeaderTitle.uploadName|escape:"url"}" {if $displayPageHeaderTitleAltText != ''}alt="{$displayPageHeaderTitleAltText|escape}"{else}alt="{translate key="common.pageHeader.altText"}"{/if} />	
        <div id="issn" style="display:inline">{translate key="journal.issn"}:&nbsp;{$issn|escape} 2236-4633</div>
      </div>
      <!-- logo -->
      <div id="logo_imagem" style="background-image: url('{$publicFilesDir}/homepageImage_pt_BR.jpg');">
	<a href="{url page="index"}">
 {php}
$browser = $_SERVER['HTTP_USER_AGENT'];
if (strstr($browser, "MSIE 6.0")) {
{/php}
{php} 
} else {
{/php}
{php}
}
{/php}
	<img src="{$publicFilesDir}/{$displayPageHeaderLogo.uploadName|escape:"url"}" {if $displayPageHeaderLogo.altText != ''}alt="{$displayPageHeaderLogo.altText|escape}"{else}alt="{translate key="common.pageHeaderLogo.altText"}"{/if}/>
	  	</a>
      </div> 
      
    </div>
  </div>    
  
  <!-- corpo -->
  <div id="body">
    
    <!-- sidebars -->
    <div id="sidebar">
      {if $leftSidebarCode}
      <div id="leftSidebar">
	{$leftSidebarCode}
      </div>
     {/if}
      <div id="navbar">
	{include file="common/menu.tpl" target=""}
      </div>
      
      {if $rightSidebarCode}
      <div id="rightSidebar">
	{$rightSidebarCode}
      </div>
      {/if}
    </div>
    
    
    <!-- meio -->
    <div id="main">
      
      {if !$hideFirst}
      <div id="breadcrumb">
	{foreach from=$pageHierarchy item=hierarchyLink}
	<a href="{$hierarchyLink[0]|escape}" class="hierarchyLink">{if not $hierarchyLink[2]}{translate key=$hierarchyLink[1]}{else}{$hierarchyLink[1]|escape}{/if}</a> &gt;
	{/foreach}
	<a href="{$currentUrl|escape}" class="current">{$pageCrumbTitleTranslated}</a>
      </div>
      {/if}
      
      {if $pageSubtitle && !$pageSubtitleTranslated}{translate|assign:"pageSubtitleTranslated" key=$pageSubtitle}{/if}
      {if $pageSubtitleTranslated}
      <h3>{$pageSubtitleTranslated}</h3>
      {/if}
      
      <div id="content">
