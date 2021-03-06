<?php

/**
 * @defgroup periodicos
 */

/**
 * @file plugins/generic/periodicos/EdicaoPeriodico.inc.php
 *
 * Copyright (c) 2010 Fernão Lopes
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class EdicaoPeriodico
 * @ingroup periodicos
 * @see PeriodicosDAO
 *
 * @brief Class for Issue.
 */

// $Id$


class PeriodicoEdicao extends DataObject {
	/**
	 * get periodico id
	 * @return int
	 */
	function getPeriodicoEdicaoId() {
		return $this->getData('periodicoEdicaoId');
	}

	/**
	 * set periodico id
	 * @param $periodicoId int
	 */
	function setPeriodicoEdicaoId($periodicoId) {
		return $this->setData('periodicoId', $periodicoId);
	}
	

	/**
	 * get periodico id
	 * @return int
	 */
	function getPeriodicoId() {
		return $this->getData('periodicoId');
	}

	/**
	 * set periodico id
	 * @param $periodicoId int
	 */
	function setPeriodicoId($periodicoId) {
		return $this->setData('periodicoId', $periodicoId);
	}

	/**
	 * get issue_id
	 * @return int
	 */
	function getIssueId() {
		return $this->getData('issueId');
	}

	/**
	 * set issueId
	 * @param $issueId int
	 */
	function setIssueId($issueId) {
		return $this->setData('issueId', $issueId);
	}
	
	/**
	 * get edicao
	 * @return string
	 */
	function getEdicao() {
		return $this->getData('edicao');
	}

	/**
	 * set edicao
	 * @param $edicao string
	 */
	function setEdicao($edicao) {
		return $this->setData('edicao', $edicao);
	}

	/**
	 * get referencia
	 * @return int
	 */
	function getReferencia() {
		return $this->getData('referencia');
	}

	/**
	 * set referencia
	 * @param $referencia int
	 */
	function setReferencia($referencia) {
		return $this->setData('referencia', $referencia);
	}

	/**
	 * get responsavel
	 * @return string
	 */
	function getResponsavel() {
		return $this->getData('responsavel');
	}
	
	/**
	 * set responsavel
	 * @return string
	 */
	function setResponsavel($responsavel) {
	  return $this->setData('responsavel', $responsavel);
	}
	
}