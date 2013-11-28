<?php

/**
 * @defgroup periodicos
 */

/**
 * @file plugins/generic/periodicos/Periodicos.inc.php
 *
 * Copyright (c) 2010 Fernão Lopes
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class Periodicos
 * @ingroup periodicos
 * @see PeriodicosDAO
 *
 * @brief Class for Issue.
 */

// $Id$


class Periodico extends DataObject {
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
	 * get journal id
	 * @return int
	 */
	function getJournalId() {
		return $this->getData('journalId');
	}

	/**
	 * set journal id
	 * @param $journalId int
	 */
	function setJournalId($journalId) {
		return $this->setData('journalId', $journalId);
	}

	
	/**
	 * get nome
	 * @return string
	 */
	function getNome() {
		return $this->getData('nome');
	}

	/**
	 * set nome
	 * @param $nome string
	 */
	function setNome($nome) {
		return $this->setData('nome', $nome);
	}

	/**
	 * get issn
	 * @return int
	 */
	function getIssn() {
		return $this->getData('issn');
	}

	/**
	 * set issn
	 * @param $issn int
	 */
	function setIssn($issn) {
		return $this->setData('issn', $issn);
	}

	/**
	 * get local
	 * @return string
	 */
	function getLocal() {
		return $this->getData('local');
	}
	
	/**
	 * set local
	 * @return string
	 */
	function setLocal($local) {
	  return $this->setData('local', $local);
	}
	
	/**
	 * get link
	 * @return string
	 */
	function getLink() {
		return $this->getData('link');
	}

	/**
	 * set link
	 * @param $link string
	 */
	function setLink($link) {
		return $this->setData('link', $link);
	}

	/**
	 * get acesso
	 * @return int
	 */
	function getAcesso() {
		return $this->getData('acesso');
	}

	/**
	 * set acesso
	 * @param $acesso int
	 */
	function setAcesso($acesso) {
		return $this->setData('acesso', $acesso);
	}
	

}