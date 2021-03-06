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
import('plugins/generic/periodicos/PeriodicoEdicao');

class PeriodicoEdicaoForm extends Form {

	/**
	 * Constructor.
	 */
	function PeriodicoEdicaoForm($template) {
		parent::Form($template);
		$this->addCheck(new FormValidatorPost($this));
	}
	
	
	/**
	 * Validate the form
	 */
	function validate() {
		if ($this->getData('edicao')) {
			$this->addCheck(new FormValidatorLocale($this, 'edicao', 'required', 'periodico.edicao'));
		}
		// check if public issue ID has already used
		$journal =& Request::getJournal();
		$periodicosDao =& DAORegistry::getDAO('PeriodicosDAO');
		
		return parent::validate();
	}
       
	
	function readInputData() {
	  $this->readUserVars(array(
				    'edicao',
				    'referencia',
				    'responsavel',
				    'periodico_id',
				    'issue_id'
				    ));
	}

	/**
	 * Save issue settings.
	 */
	function execute($periodicoEdicaoId = 0) {
		$periodicosDao =& DAORegistry::getDAO('PeriodicosDAO');
		
		if ($periodicoEdicaoId) {
			$periodicoEdicao = $periodicosDao->getPeriodicoEdicao($periodicoEdicaoId);
			$isNewPeriodico = false;
		} else {
		  $periodicoEdicao = array();
		  $isNewIssue = true;
		}
		
		$edicao = $this->getData('edicao');
		$responsavel = $this->getData('responsavel');
		$periodicoId = $this->getData('periodico_id');
		$issueId = $this->getData('issue_id');
		
		$periodicoEdicao['edicao'] = $edicao;
		$periodicoEdicao['responsavel'] = $responsavel;
		$periodicoEdicao['periodico_id'] = $periodicoId;
		$periodicoEdicao['issue_id'] = $issueId;
		// if issueId is supplied, then update issue otherwise insert a new one
		
		if ($periodicoEdicaoId != 0) {
		  $periodicoEdicao['periodico_edicao_id'] = $periodicoEdicaoId;
		  $periodicosDao->updatePeriodicoEdicao($periodicoEdicao);
		} else {
		  $periodicoEdicaoId = $periodicosDao->insertPeriodicoEdicao($periodicoEdicao);
		}
		
		return $periodicoEdicaoId;
	}
}

?>
