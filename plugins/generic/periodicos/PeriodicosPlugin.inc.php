<?php

/**
 * @file PeriodicosPlugin.inc.php
 *
 * Copyright (c) 2003-2009 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PeriodicosPlugin
 * @ingroup plugins_generic_sehl
 *
 * @brief Plugin para periódicos Almanack
 */

// $Id: PeriodicosPlugin.inc.php,v 1.4.2.1 2009/04/08 19:43:17 asmecher Exp $


import('classes.plugins.GenericPlugin');

class PeriodicosPlugin extends GenericPlugin {
	/** @var $queryTerms string */
	var $queryTerms;

	function register($category, $path) {
	  
	  $isEnabled = true;// $this->getSetting(0, 'enabled');
	  $success = parent::register($category, $path);
	  
	  if ($success && $isEnabled === true) {	  
	    
	    HookRegistry::register ('LoadHandler', array(&$this, 'handleRequest'));
    
	    $this->import('PeriodicosDAO');
	    $periodicos = &new PeriodicosDAO();
	    DAORegistry::registerDAO('PeriodicosDAO', $periodicos);
	    
	    $journal =& Request::getJournal();
	    $journalId = $journal?$journal->getJournalId():0;
	    $isEnabled = $this->getSetting($journalId, 'enabled');

	    $this->addLocaleData();
	    
	    return true;
	  }
	  return false;
	}

	function getName() {
		return 'PeriodicosPlugin';
	}
	
	function getDisplayName() {
		return Localex::translate('plugins.generic.periodicos.name');
	}

	function getDescription() {
		return Localex::translate('plugins.generic.periodicos.description');
	}

	function getEnabled() {
		$journal =& Request::getJournal();
		$journalId = $journal?$journal->getJournalId():0;
		return $this->getSetting($journalId, 'enabled');
	}

	function handleRequest($hookName, $args) {
		$page =& $args[0];
		$op =& $args[1];
		$sourceFile =& $args[2];

		// If the request is for the log analyzer itself, handle it.
		if ($page === 'periodicos') {
			$this->addLocaleData();
			$this->import('PeriodicosHandler');
			Registry::set('plugin', $this);
			define('HANDLER_CLASS', 'PeriodicosHandler');
			return true;
		}
		
		return false;
	}

	function isSitePlugin() {
		return true;
	}

	function getManagementVerbs() {
		return array(array(
			($this->getEnabled()?'disable':'enable'),
			Localex::translate($this->getEnabled()?'manager.plugins.disable':'manager.plugins.enable')
		));
	}

	function getInstallSchemaFile() {
		return $this->getPluginPath() . '/' . 'schema.xml';
	}
	
	function manage($verb, $args) {
		$journal =& Request::getJournal();
		$journalId = $journal?$journal->getJournalId():0;
		switch ($verb) {
			case 'enable':
				$this->updateSetting($journalId, 'enabled', true);
				break;
			case 'disable':
				$this->updateSetting($journalId, 'enabled', false);
				break;
		}
		return false;
	}
}

?>
