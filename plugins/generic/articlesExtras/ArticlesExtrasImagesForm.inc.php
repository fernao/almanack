<?php

import('form.Form');
import('file.ArticleFileManager');

class ArticlesExtrasImagesForm extends Form {
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
	function ArticlesExtrasImagesForm(&$plugin, $journalId) {
		parent::Form($plugin->getTemplatePath() . 'templates/imagesForm.tpl');

		$this->journalId = $journalId;
		$this->plugin =& $plugin;
		
	}

	/**
	 * Initialize form data from current group group.
	 */
	function initData( $args ) {
		// Figure out the current article 
		$current = array_shift($args);
		$this->setData('current', $current);
		
		$plugin =& $this->plugin;
		$plugin->import("ArticlesExtrasDAO");
		$plugin->import("classes/Image");
		
		$articlesExtrasDao = new ArticlesExtrasDAO();
		$images = $articlesExtrasDao->getArticleImages($current);
		if(!empty($images)){
			$images = unserialize($images);
		}

		$templateMgr = &TemplateManager::getManager();
		$templateMgr->assign('current', $current );
		$templateMgr->assign_by_ref('images', $images );
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('filename', 'name', 'description', 'current'));
	}

	/**
	 * Save page - write to content file. 
	 */	 
	function save() {
		$current = $this->getData('current');
		$plugin =& $this->plugin;
		
		$articleDao = &DAORegistry::getDAO('PublishedArticleDAO');
		$article = &$articleDao->getPublishedArticleByArticleId($current, null, false);	
		
		$plugin->import("ArticlesExtrasDAO");
		$plugin->import("classes/Image");
		
		$articleExtrasDao = new ArticlesExtrasDAO();
		
		$images = array();
		if($articleExtrasDao->countImagesByArticleId($current) > 0)
			$images = unserialize($articleExtrasDao->getArticleImages($current));
		
	
		//Upload image		
		$articleFileManager = &new ArticleFileManager($current);
		if($fileId = $articleFileManager->uploadPublicFile('filename')){
			//Make new
			$newImage = new Image($this->getData('name'), $this->getData('description'), $fileId);
							
			//Add images
			$images[] = $newImage;
			$articleExtrasDao->setArticleImages($article, serialize($images));
		}
	}

}
?>
