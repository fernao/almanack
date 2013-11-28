<?php

/**
 * @file ArticlesExtrasHandler.php
 *
 * Copyright (c) 2009 Richard González Alberto
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_articlesExtras
 * @brief Articles Extras generic plugin Handler.
 *
 */


import('core.Handler');
import('file.ArticleFileManager');

class ArticlesExtrasHandler extends Handler {

	/**
	 * Display ArticlesExtras index page.
	 */
	function index() {
		ArticlesExtrasHandler::validate();
		ArticlesExtrasHandler::setupTemplate();
		$journal = &Request::getJournal();

		if ($journal != null) {
			$journalId = $journal->getJournalId();
		} else {
			Request::redirect(null, 'index');
		}

		$articlesExtrasPlugin = &PluginRegistry::getPlugin('generic', 'ArticlesExtrasPlugin');

		if ($articlesExtrasPlugin != null) {
			$articlesExtrasEnabled = $articlesExtrasPlugin->getEnabled();
		}

		if ($articlesExtrasEnabled) {
			$templateMgr = &TemplateManager::getManager();
			$templateMgr->display($articlesExtrasPlugin->getTemplatePath() . 'templates/index.tpl');
		} else {
			Request::redirect(null, 'index');
		}

	}
	
	/**
	 * Display a list of the current issues.
	 */
	function listIssues($args = array()) {
		ArticlesExtrasHandler::validate();
		ArticlesExtrasHandler::setupTemplate();
		$journal = &Request::getJournal();

		if ($journal != null) {
			$journalId = $journal->getJournalId();
		} else {
			Request::redirect(null, 'index');
		}

		$articlesExtrasPlugin = &PluginRegistry::getPlugin('generic', 'ArticlesExtrasPlugin');

		if ($articlesExtrasPlugin != null) {
			$articlesExtrasEnabled = $articlesExtrasPlugin->getEnabled();
		}

		if ($articlesExtrasEnabled) {
			$editType = array_shift($args);
			$journal = &Request::getJournal();
			$issueDao = &DAORegistry::getDAO('IssueDAO');
			$issues = &$issueDao->getIssues($journal->getJournalId(), Handler::getRangeInfo('issues'));
	
			$templateMgr = &TemplateManager::getManager();
			$templateMgr->assign('editType', $editType);
			$templateMgr->assign_by_ref('issues', $issues);
			$templateMgr->display($articlesExtrasPlugin->getTemplatePath() . 'templates/issues.tpl');
		} else {
			Request::redirect(null, 'index');
		}
	}
	
	/**
	 * Display a list of articles from the selected issue.
	 */
	function listArticles($args = array()) {
		ArticlesExtrasHandler::validate();
		ArticlesExtrasHandler::setupTemplate();
		$journal = &Request::getJournal();
		
		if ($journal != null) {
			$journalId = $journal->getJournalId();
		} else {
			Request::redirect(null, 'index');
		}

		$articlesExtrasPlugin = &PluginRegistry::getPlugin('generic', 'ArticlesExtrasPlugin');

		if ($articlesExtrasPlugin != null) {
			$articlesExtrasEnabled = $articlesExtrasPlugin->getEnabled();
		}

		if ($articlesExtrasEnabled) {
			$issueId = array_shift($args);
			$editType = array_shift($args);
			$publishedArticleDao = &DAORegistry::getDAO('PublishedArticleDAO');
			$articles = &$publishedArticleDao->getPublishedArticles($issueId, null, false);
			
			$templateMgr = &TemplateManager::getManager();
			$templateMgr->assign_by_ref('articles', $articles);
			$templateMgr->assign('editType', $editType);
			$templateMgr->display($articlesExtrasPlugin->getTemplatePath() . 'templates/articles.tpl');
		} else {
			Request::redirect(null, 'index');
		}
	}
	
	/**
	 * Show body submit form.
	 */
	function submitBody($args = array()) {
		ArticlesExtrasHandler::validate();
		ArticlesExtrasHandler::setupTemplate();
		$journal = &Request::getJournal();

		if ($journal != null) {
			$journalId = $journal->getJournalId();
		} else {
			Request::redirect(null, 'index');
		}

		$articlesExtrasPlugin = &PluginRegistry::getPlugin('generic', 'ArticlesExtrasPlugin');

		if ($articlesExtrasPlugin != null) {
			$articlesExtrasEnabled = $articlesExtrasPlugin->getEnabled();
		}

		if ($articlesExtrasEnabled) {
			$articlesExtrasPlugin->import('ArticlesExtrasBodyForm');
			$form =& new ArticlesExtrasBodyForm($articlesExtrasPlugin, $journal->getJournalId());
			
                        $form->initData($args);
			$form->display();
		} else {
			Request::redirect(null, 'index');
		}
	}

	/**
	 * Save submitted Body.
	 */
	function saveBody($args = array()) {
		ArticlesExtrasHandler::validate();
		$journal = &Request::getJournal();

		if ($journal != null) {
			$journalId = $journal->getJournalId();
		} else {
			Request::redirect(null, 'index');
		}

		$articlesExtrasPlugin = &PluginRegistry::getPlugin('generic', 'ArticlesExtrasPlugin');

		if ($articlesExtrasPlugin != null) {
			$articlesExtrasEnabled = $articlesExtrasPlugin->getEnabled();
		}

		if ($articlesExtrasEnabled) {
			$articlesExtrasPlugin->import('ArticlesExtrasBodyForm');
			$form =& new ArticlesExtrasBodyForm($articlesExtrasPlugin, $journal->getJournalId());

			// saving and staying on the form
			if ( Request::getUserVar('body') ) {
				$form->readInputData();
				
				if ($form->validate()) {
					// perform the save and reset the form
					$form->save();
					$form->initData( array($form->getData('current')) );	
				} else {
					// add the tiny MCE script to the form 
					$form->addTinyMCE();
											
					$templateMgr->assign('currentBody', Request::getUserVar('body'));
				}
				$form->display();
			} else {					
				$form->initData($args);
				$form->display();
			}

		} else {
				Request::redirect(null, 'index');
		}	
	}

        /**
	 * Show images submit form.
	 */
	function submitImages($args = array()) {
		ArticlesExtrasHandler::validate();
		ArticlesExtrasHandler::setupTemplate();
		$journal = &Request::getJournal();

		if ($journal != null) {
			$journalId = $journal->getJournalId();
		} else {
			Request::redirect(null, 'index');
		}

		$articlesExtrasPlugin = &PluginRegistry::getPlugin('generic', 'ArticlesExtrasPlugin');

		if ($articlesExtrasPlugin != null) {
			$articlesExtrasEnabled = $articlesExtrasPlugin->getEnabled();
		}

		if ($articlesExtrasEnabled) {
			//catch delete
			foreach($args as $arg){
				if($arg == "delete") {
					$articleId =$args[0];
					$fileId = $args[2];
					ArticlesExtrasHandler::deleteImage($articleId, $fileId);
				}
			}

			$articlesExtrasPlugin->import('ArticlesExtrasImagesForm');
			$form =& new ArticlesExtrasImagesForm($articlesExtrasPlugin, $journal->getJournalId());

			$form->initData($args);
			$form->display();
		} else {
			Request::redirect(null, 'index');
		}
	}

        /**
	 * Delete an Image.
         * @param $articleId
         * @param $fileId
	 */
        function deleteImage($articleId, $fileId){
		ArticlesExtrasHandler::validate();

		//article
		$articleDao = &DAORegistry::getDAO('PublishedArticleDAO');
		$article = &$articleDao->getPublishedArticleByArticleId($articleId, null, false);

		//plugin
		$plugin = &PluginRegistry::getPlugin('generic', 'ArticlesExtrasPlugin');
		$plugin->import("ArticlesExtrasDAO");
		$plugin->import("classes/Image");

		//articlesExtras
		$articlesExtrasDao = new ArticlesExtrasDAO();

		//Get current
		$images = unserialize($articlesExtrasDao->getArticleImages($articleId));

		//Delete from array
		$newImages = array();
		foreach($images as $image){
			if($image->getFileId() != $fileId) $newImages[] = $image;
		}

		//Delete file
		$articleFileManager = &new ArticleFileManager($articleId);
		$articleFileManager->deleteFile($fileId);

		// Make update
		$articlesExtrasDao->setArticleImages($article, serialize($newImages));

		//Refresh
		Request::redirect(null, 'ArticlesExtrasPlugin', 'submitImages', array($articleId));
	}

	/**
	 * Save submitted Images.
	 */
	function saveImages() {
		ArticlesExtrasHandler::validate();
		$journal = &Request::getJournal();

		if ($journal != null) {
			$journalId = $journal->getJournalId();
		} else {
			Request::redirect(null, 'index');
		}

		$articlesExtrasPlugin = &PluginRegistry::getPlugin('generic', 'ArticlesExtrasPlugin');

		if ($articlesExtrasPlugin != null) {
			$articlesExtrasEnabled = $articlesExtrasPlugin->getEnabled();
		}

		if ($articlesExtrasEnabled) {
			$articlesExtrasPlugin->import('ArticlesExtrasImagesForm');
			$form =& new ArticlesExtrasImagesForm($articlesExtrasPlugin, $journal->getJournalId());

			// saving and staying on the form
			if ( Request::getUserVar('name') ) {
				$form->readInputData();

				if ($form->validate()) {
					// perform the save and reset the form
					$form->save();
					$form->initData( array($form->getData('current')) );
				}

				$form->display();
			} else {
				$form->initData($args);
				$form->display();
			}

		} else {
				Request::redirect(null, 'index');
		}
	}
	
	/**
	 * Show citations submit form.
	 */
	function submitCitations($args = array()) {
		ArticlesExtrasHandler::validate();
		ArticlesExtrasHandler::setupTemplate();
		$journal = &Request::getJournal();

		if ($journal != null) {
			$journalId = $journal->getJournalId();
		} else {
			Request::redirect(null, 'index');
		}

		$articlesExtrasPlugin = &PluginRegistry::getPlugin('generic', 'ArticlesExtrasPlugin');

		if ($articlesExtrasPlugin != null) {
			$articlesExtrasEnabled = $articlesExtrasPlugin->getEnabled();
		}

		if ($articlesExtrasEnabled) {
			$articlesExtrasPlugin->import('ArticlesExtrasCitationsForm');
			$form =& new ArticlesExtrasCitationsForm($articlesExtrasPlugin, $journal->getJournalId());
			
			$form->initData($args);
			$form->display();			
		} else {
			Request::redirect(null, 'index');
		}
	}
	
		

	/**
	 * Setup common template variables.
	 * @param $subclass boolean set to true if caller is below this handler in the hierarchy
	 */
	function setupTemplate($subclass = false) {
		parent::validate();

		$templateMgr = &TemplateManager::getManager();
		$templateMgr->assign('pageHierachy', array(array(Request::url(null, 'ArticlesExtrasPlugin'), 'plugins.generic.plugins.generic.articlesExtras.displayName')));
	}
	
	/**
	 * Validate that user is an editor/admin/manager/layout_editor in the selected journal.
	 * Redirects to user index page if not properly authenticated.
	 */
	function validate() {
		$journal = &Request::getJournal();
		if (!isset($journal) || ( !Validation::isEditor($journal->getJournalId()) && !Validation::isSiteAdmin() && !Validation::isJournalManager($journal->getJournalId()) && !Validation::isLayoutEditor($journal->getJournalId()) )) {
			Validation::redirectLogin();
		}
	}
}

?>
