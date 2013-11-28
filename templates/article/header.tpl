{**
 * header.tpl
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Article View -- Header component.
 *}
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$article->getLocalizedTitle()|escape} | {$article->getFirstAuthor(true)|escape} | {$currentJournal->getLocalizedTitle()|escape}</title>
	<meta name="description" content="{$article->getLocalizedTitle()|strip_tags|escape}" />
	<meta name="description" content="{$article->getArticleTitle()|escape}" />
	{if $article->getLocalizedSubject()}
		<meta name="keywords" content="{$article->getLocalizedSubject()|escape}" />
	{/if}
	{if $displayFavicon}<link rel="icon" href="{$faviconDir}/{$displayFavicon.uploadName|escape:"url"}" />{/if}

	{include file="article/dublincore.tpl"}
	{include file="article/googlescholar.tpl"}
	{call_hook name="Templates::Article::Header::Metadata"}

	<link rel="stylesheet" href="{$baseUrl}/lib/pkp/styles/pkp.css" type="text/css" />
	<link rel="stylesheet" href="{$baseUrl}/lib/pkp/styles/common.css" type="text/css" />
	<link rel="stylesheet" href="{$baseUrl}/styles/common.css" type="text/css" />
	<link rel="stylesheet" href="{$baseUrl}/styles/articleView.css" type="text/css" />
	{if $journalRt && $journalRt->getEnabled()}
		<link rel="stylesheet" href="{$baseUrl}/lib/pkp/styles/rtEmbedded.css" type="text/css" />
	{/if}

	{if $leftSidebarCode || $rightSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/sidebar.css" type="text/css" />{/if}
	{if $leftSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/leftSidebar.css" type="text/css" />{/if}
	{if $rightSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/rightSidebar.css" type="text/css" />{/if}
	{if $leftSidebarCode && $rightSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/bothSidebars.css" type="text/css" />{/if}
	{foreach from=$stylesheets item=cssUrl}

	<link rel="stylesheet" href="{$cssUrl}" type="text/css" />
	{/foreach}

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

	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/fontController.js" ></script>
	<script type="text/javascript">{literal}
		$(function(){
			fontSize("#sizer", "body", 9, 16, 32, "{/literal}{$basePath|escape:"javascript"}{literal}"); // Initialize the font sizer
		});
	{/literal}</script>

	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/general.js"></script>
	{$additionalHeadData}

<!--
	{call_hook|assign:"leftSidebarCode" name="Templates::Common::LeftSidebar"}
	{call_hook|assign:"rightSidebarCode" name="Templates::Common::RightSidebar"}
-->
</head>

<body class="article_page">

<div id="container">

<div id="body">
  

 <!-- header comum -->
  <div id="header">
    <div id="headerTitle">
      <!-- almanack braziliense -->
      <div id="logo_texto">
	<a href="{url page="index"}">
	  <img src="{$publicFilesDir}/logo_preto.png" {if $displayPageHeaderTitle.altText != ''}alt="{$displayPageHeaderTitle.altText|escape}"{else}alt="{translate key="common.pageHeader.altText"}"{/if} />
	</a>
	<!-- revista eletronica -->

	<img src="{$publicFilesDir}/{$displayPageHeaderTitle.uploadName|escape:"url"}" {if $displayPageHeaderTitleAltText != ''}alt="{$displayPageHeaderTitleAltText|escape}"{else}alt="{translate key="common.pageHeader.altText"}"{/if} />	

        <div id="issn" style="display:inline">{translate key="journal.issn"}:&nbsp;{$issn|escape} </div>
      </div>
      
      <!-- logo -->
{php}
$browser = $_SERVER['HTTP_USER_AGENT'];
$ie6 = strstr($browser, 'MSIE 6.0');

if ($ie6) { {/php}
      <div id="logo_imagem" style="filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='{$publicFilesDir}/homepageImage_pt_BR.jpg', sizingMethod='scale');">

{php} } else {/php}
      <div id="logo_imagem" style="background-image: url('{$publicFilesDir}/homepageImage_pt_BR.jpg');">
{/php}
	<a href="{url page="index"}" target="_parent">
	  <img src="{$publicFilesDir}/{$displayPageHeaderLogo.uploadName|escape:"url"}" {if $displayPageHeaderLogo.altText != ''}alt="{$displayPageHeaderLogo.altText|escape}"{else}alt="{translate key="common.pageHeaderLogo.altText"}"{/if} />
	</a>
      </div> 
      
    </div>
  </div>    

  <div id="sidebar">
    <div id="navbar">
      {include file="common/menu.tpl" target="_parent"}
    </div>
  </div>

<div id="main">

<div id="breadcrumb">
	<a href="{url page="index"}" target="_parent">{translate key="navigation.home"}</a> &gt;
	{if $issue}<a href="{url page="issue" op="view" path=$issue->getBestIssueId($currentJournal)}/showToc" target="_parent">{$issue->getIssueIdentification(false,true)|strip_unsafe_html|nl2br}</a> &gt;{/if}
	<a href="{url page="article" op="view" path=$articleId|to_array:$galleyId}" class="current" target="_parent">{$article->getFirstAuthor(true)|escape}</a>
</div>


  <div class="rt">
    {$rightSidebarCode}
  </div>
{*
  <div class="rt">
    {include file="rt/rt.tpl"}
  </div>
*}
<div id="content">

