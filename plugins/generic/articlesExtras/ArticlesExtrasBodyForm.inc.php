<?php

import('form.Form');
import('file.ArticleFileManager');

class ArticlesExtrasBodyForm extends Form {
	/** @var $journalId int */
	var $journalId;

	/** @var $plugin object */
	var $plugin;

	/** $var $errors string */
	var $errors;

	/**
	 * Constructor
	 * @param $journalId int
	 */
	function ArticlesExtrasBodyForm(&$plugin, $journalId) {

		parent::Form($plugin->getTemplatePath() . 'templates/bodyForm.tpl');

		$this->journalId = $journalId;
		$this->plugin =& $plugin;
	}

	/**
	 * Initialize form data from current group group.
	 */
	function initData( $args ) {
		// figure out the current page 
		$current = array_shift($args);
		$this->setData('current', $current);
		
		$plugin =& $this->plugin;
		$plugin->import("ArticlesExtrasDAO");
		$articlesExtrasDao = new ArticlesExtrasDAO();
		$body = $articlesExtrasDao->getArticleBody($current);
		
		// add the tiny MCE script 
		HookRegistry::register('TemplateManager::display',array(&$this, 'callback'));
		
		$templateMgr = &TemplateManager::getManager();
		$templateMgr->assign('current', $current );
		$templateMgr->assign('currentBody', $body);
	}
	
	/**
	 * Hook callback function for TemplateManager::display
	 * @param $hookName string
	 * @param $args array
	 * @return boolean
	 */
	function callback($hookName, $args) {
		$templateManager =& $args[0];

		$baseUrl = $templateManager->get_template_vars('baseUrl');
		$additionalHeadData = $templateManager->get_template_vars('additionalHeadData');

		$tinymceScript = '
		<script language="javascript" type="text/javascript" src="'.$baseUrl.'/'.TINYMCE_JS_PATH.'/tiny_mce_gzip.js"></script>
		<script language="javascript" type="text/javascript">
			tinyMCE_GZ.init({
				relative_urls : "true",
				plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
				themes : "advanced",
				disk_cache : true
			});
		</script>
		
		<script language="javascript" type="text/javascript">
			tinyMCE.init({
				plugins : "safari,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
				mode : "textareas",
				relative_urls : true,
				forced_root_block : false,
				apply_source_formatting : false,
				theme : "advanced",
				valid_elements : "p,font[face=verdana|size=2],b/strong,i/em,sup,sub,small,ul,ol,li,hr[size=1|noshade],br,img[src],a[name|href]",
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist",
				theme_advanced_buttons2 : "outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,ltr,rtl,|,fullscreen",
				theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
			});
		</script>';

		$templateManager->assign('additionalHeadData', $additionalHeadData."\n".$tinymceScript);


		return false;
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('body', 'current'));
	}

	/**
	 * Save page - write to database. 
	 */	 
	function save() {
		$current = $this->getData('current');
		$plugin =& $this->plugin;
		
		//Article
		$articleDao = &DAORegistry::getDAO('PublishedArticleDAO');
		$article = &$articleDao->getPublishedArticleByArticleId($current, null, false);	
		
		//Get body from form
		$body = addslashes($this->getData('body'));
		
		//ArticlesExtras DAO
		$plugin->import("ArticlesExtrasDAO");
		$articleDao = new ArticlesExtrasDAO();
		
		//Set body
		$articleDao->setArticleBody($article, $body);
	}

}
?>
