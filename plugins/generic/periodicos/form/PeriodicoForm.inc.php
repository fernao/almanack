<?php

/**
 * @defgroup issue_form
 */

/**
 * @file classes/form/IssueForm.inc.php
 *
 * Copyright (c) 2003-2009 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class IssueForm
 * @ingroup issue_form
 * @see Issue
 *
 * @brief Form to create or edit an issue
 */

// $Id$


import('form.Form');
import('plugins/generic/periodicos/Periodico');
//import('issue.Issue'); // Bring in constants

class PeriodicoForm extends Form {

	/**
	 * Constructor.
	 */
	function PeriodicoForm($template) {
		parent::Form($template);
		$this->addCheck(new FormValidatorPost($this));
	}
	
	
	/**
	 * Validate the form
	 */
	// TODO: esta quebrado - fazer funcionar ?
	function validate() {
		if ($this->getData('nome')) {
			$this->addCheck(new FormValidatorLocale($this, 'nome', 'required', 'periodico.nome'));
		}
		// check if public issue ID has already used
		$journal =& Request::getJournal();
		$periodicosDao =& DAORegistry::getDAO('PeriodicosDAO');
		
		$nome = $this->getData('nome');
		$periodicoId = $this->getData('periodico_id');
		
		if (($nome && !$periodicoId) && $periodicosDao->periodicoNomeExists($nome, $journal->getJournalId())) {
		  $this->addError('nome', Localex::translate('plugins.generic.periodicos.admin.erro.nome_repetido'));
		  $this->addErrorField('nome');
		}
		return parent::validate();
	}
	
	
	function readInputData() {
	  $this->readUserVars(array(
				    'nome',
				    'local',
				    'issn',
				    'acesso',
				    'link',
				    'journal_id',
				    'periodico_id'
				    ));
	}

	/**
	 * Save issue settings.
	 */
	function execute() {
		$journal =& Request::getJournal();
		$periodicosDao =& DAORegistry::getDAO('PeriodicosDAO');
		
		if ($periodicoId) {
			$periodico = $periodicosDao->getPeriodico($periodicoId);
			$isNewPeriodico = false;
		} else {
			$periodico = new Periodico();
			$isNewIssue = true;
		}
		
		$nome = $this->getData('nome');
		$local = $this->getData('local');
		$acesso = $this->getData('acesso');
		$link = $this->getData('link');
		$issn = $this->getData('issn');
		$journalId = $this->getData('journal_id');
		$periodicoId = $this->getData('periodico_id');
		
		$periodico->setNome($nome);
		$periodico->setLocal($local);
		$periodico->setAcesso($acesso);
		$periodico->setLink($link);
		$periodico->setIssn($issn);
		$periodico->setJournalId($journal->getJournalId());
		
		// if issueId is supplied, then update issue otherwise insert a new one
		if ($periodicoId) {
		  $periodico->setPeriodicoId($periodicoId);
		  $periodicosDao->updatePeriodico($periodico);
		} else {
		  $periodicoId = $periodicosDao->insertPeriodico($periodico);
		  $periodico->setPeriodicoId($periodicoId);
		}
		
		return $periodicoId;
	}
}

?>
