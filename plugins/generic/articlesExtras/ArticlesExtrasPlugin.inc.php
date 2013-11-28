<?php

/**
 * @file ArticlesExtrasPlugin.php
 *
 * Copyright (c) 2009 Richard González Alberto
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_articlesExtras
 * @brief Articles Extras generic plugin.
 *
 */

import('classes.plugins.GenericPlugin');

class ArticlesExtrasPlugin extends GenericPlugin {

	function getName() {
		return 'ArticlesExtrasPlugin';
	}

	function getDisplayName() {
		return Localex::translate('plugins.generic.articlesExtras.displayName');
	} 		

	function getDescription() {
		$description = Localex::translate('plugins.generic.articlesExtras.description');
		if ( !$this->isTinyMCEInstalled() )
			$description .= "<br />".Localex::translate('plugins.generic.articlesExtras.requirement.tinymce');
		return $description;
	}
	
	function isTinyMCEInstalled() {
		$tinyMCEPlugin = &PluginRegistry::getPlugin('generic', 'TinyMCEPlugin');

		if ( $tinyMCEPlugin ) 
			return $tinyMCEPlugin->getEnabled();

		return false;
	}

	/**
	 * Register the plugin, attaching to hooks as necessary.
	 * @param $category string
	 * @param $path string
	 * @return boolean
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {		
			$this->addLocaleData();
			if ($this->getEnabled()) {
				$this->import('ArticlesExtrasDAO');
				$articlesExtrasDao = &new ArticlesExtrasDAO();
				$returner = &DAORegistry::registerDAO('ArticlesExtrasDAO', $articlesExtrasDao);
				
				// Handler for public thesis abstract pages
				HookRegistry::register('LoadHandler', array($this, 'setupPublicHandler'));	
							
				// Editor page for editor access
				HookRegistry::register('Templates::Editor::Index::Issues', array($this, 'displayEditorLink'));
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Determine whether or not this plugin is enabled.
	 */
	function getEnabled() {
		$journal = &Request::getJournal();
		if (!$journal) return false;
		return $this->getSetting($journal->getJournalId(), 'enabled');
	}

	/**
	 * Set the enabled/disabled state of this plugin
	 */
	function setEnabled($enabled) {
		$journal = &Request::getJournal();
		if ($journal) {
			$this->updateSetting($journal->getJournalId(), 'enabled', $enabled ? true : false);
			return true;
		}	
		return false;
	}

	/**
	 * Display verbs for the management interface.
	 */
	function getManagementVerbs() {
		$verbs = array();
		if ($this->getEnabled()) {
			$verbs[] = array(
				'disable',
				Localex::translate('manager.plugins.disable')
			);
		} else {
			$verbs[] = array(
				'enable',
				Localex::translate('manager.plugins.enable')
			);
		}
		return $verbs;
	}

	/**
	 * Perform management functions
	 */
	function manage($verb, $args) {
		$returner = true;

		switch ($verb) {
			case 'enable':
				$this->setEnabled(true);
				$returner = false;
				break;
			case 'disable':
				$this->setEnabled(false);
				$returner = false;
				break;
		}

		return $returner;
	}
	
	/**
	 * Displays a link to the plugin on the editor's page
	 */
	function displayEditorLink($hookName, $params) {
		if ($this->getEnabled()) {
			$smarty = &$params[1];
			$output = &$params[2];
			$url = TemplateManager::smartyUrl(array('page'=>'ArticlesExtrasPlugin'), $smarty);
			$output .= '<li>&#187; <a href="'.$url.'">' . TemplateManager::smartyTranslate(array('key'=>'plugins.generic.articlesExtras.displayName'), $smarty) . '</a></li>';
		}
		return false;
	}
	
	/**
	 * Setup plublic handler
	 */
	function setupPublicHandler($hookName, $params) {
		$page = &$params[0];
		if ($page == 'ArticlesExtrasPlugin') {
			define('HANDLER_CLASS', 'ArticlesExtrasHandler');
			$handlerFile = &$params[2];
			$handlerFile = $this->getPluginPath() . '/' . 'ArticlesExtrasHandler.inc.php';
		}
	}
}

?>
