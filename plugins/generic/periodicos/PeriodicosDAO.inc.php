<?php

/**
 * @file PeriodicosDAO.inc.php
 *
 * Copyright (c) 2010 - Fernão Lopes
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PeriodicosDAO
 * @ingroup plugins_generic_periodicos
 *
 * @brief Métodos para periódicos em revista
 */

// $Id


//import('classes.issue.IssueDAO');


class PeriodicosDAO extends DAO {
	/**
	 * Internal function to return an Periodicos object from an article_id
	 * @param $articleId int
	 * @return Periodicos
	 */

  function _getPeriodicos($sortBy = '', $order = '') {
    
    $sort_fields = array('periodico_id',
			 'journal_id',
			 'nome',
			 'issn', 
			 'acesso',
			 'local',
			 'link'
			 );


    if (in_array($sortBy, $sort_fields)) {
      $order = ($order == 'DESC') ? $order : 'ASC';
      $sort_str = "ORDER BY $sortBy $order";
    }

    $result =&$this->retrieve("SELECT * FROM periodicos $sort_str");
    
    $periodicos = array();
    while (!$result->EOF) {
      $periodicos[] =& $result->GetRowAssoc(false);
      $result->MoveNext();
    }
    
    $result->Close();
    unset($result);
    
    return $periodicos;

  }

  function _getPeriodicosFromIssue($issueId, $qtd = false) {
    
    if (!$qtd) {
      $result = &$this->retrieve(
				 'SELECT p.nome, p.periodico_id, pe.edicao, pe.periodico_edicao_id FROM periodicos p LEFT JOIN periodicos_edicao pe ON pe.periodico_id = p.periodico_id WHERE pe.issue_id = ? ORDER BY p.nome',
				 array(array($issueId)));
    } else {
      $result = &$this->retrieve(
	       'SELECT p.nome, p.periodico_id, COUNT(pa.periodico_artigo_id) AS qtd 
                  FROM periodicos p 
                  LEFT JOIN periodicos_edicao pe ON pe.periodico_id = p.periodico_id 
                  LEFT JOIN periodicos_artigo pa ON pa.periodico_edicao_id = pe.periodico_edicao_id
                WHERE pe.issue_id = ? GROUP BY p.nome, p.periodico_id ORDER BY p.nome',
   				 array(array($issueId)));
    }
    
    $periodicos = array();
    
    while (!$result->EOF) {
      $periodicos[] =& $result->GetRowAssoc(false);
      $result->MoveNext();
    }
    
    $result->Close();
    unset($result);
    
    return $periodicos;
  }


  function _getArtigosFromPeriodicoEdicao($periodicoEdicaoId) {
    if (is_numeric($periodicoEdicaoId)) {
	
      $result =&$this->retrieve('SELECT pa.referencia as referencia_artigo, pa.article_id, pa.periodico_artigo_id 
                                  FROM periodicos_edicao pe 
                               LEFT JOIN periodicos_artigo pa ON pe.periodico_edicao_id = pa.periodico_edicao_id 
                               WHERE pe.periodico_edicao_id = ?',
				array($periodicoEdicaoId)
				);
    }
    $articles = array();
    
    while (!$result->EOF) {
      $articles[] =& $result->GetRowAssoc(false);
      $result->MoveNext();
    }
    
    $result->Close();
    unset($result);
    
    return $articles;
    
  }

  function _getArtigosFromPeriodico($periodicoId, $issueId = false) {
    // TODO: fix
    if ($issueId) {
      $result = &$this->retrieve(
	       'SELECT pa.referencia AS ref_artigo, pa.article_id, p.nome, p.issn, p.acesso, p.local, p.link, p.periodico_id, pe.edicao, pe.responsavel, pe.referencia AS ref_edicao
                   FROM periodicos p 
                LEFT JOIN periodicos_edicao pe ON pe.periodico_id = p.periodico_id 
                LEFT JOIN periodicos_artigo pa ON pe.periodico_edicao_id = pa.periodico_edicao_id
                   WHERE p.periodico_id = ? AND pe.issue_id = ?
                ORDER BY pe.edicao, pa.referencia ASC',
	       array($periodicoId, $issueId));
    } else {
    $result = &$this->retrieve(
    // TODO: fix
	       'SELECT pa.* 
                   FROM periodicos_artigo pa 
                LEFT JOIN periodicos p ON p.periodico_id = pa.periodico_id
                   WHERE pa.periodico_id = ?',
	       array($periodicoId));
    }
    
    $articles = array();
    
    while (!$result->EOF) {
      $articles[] =& $result->GetRowAssoc(false);
      $result->MoveNext();
    }
    
    $result->Close();
    unset($result);
    
    return $articles;
  }

  function getPeriodico($periodicoId) {
    return $this->_getPeriodicoFrom('periodico_id', $periodicoId);
  }

  function _getPeriodicoFrom($fieldToSearch, $valueToSearch) {
    
    switch($fieldToSearch) {
      
    case 'issn': 
            
      $result = &$this->retrieve("SELECT * FROM periodicos WHERE issn LIKE '%$valueToSearch%'");
      break;
      
    case 'titulo':
      //      print "SELECT * FROM periodicos WHERE nome LIKE '%$valueToSearch%'\n";
      $result = &$this->retrieve("SELECT * FROM periodicos WHERE nome LIKE '%$valueToSearch%'");
      break;

    case 'periodico_id':
      $result = &$this->retrieve("SELECT * FROM periodicos WHERE periodico_id = '$valueToSearch'");
      break;
    }

  
    $returner = null;
    if ($result->RecordCount() != 0) {
      $returner = $result->GetRowAssoc(false);
    }
    
    $result->Close();
    unset($result);
    
    return $returner;
  }

  function _getPeriodicoArticleId($str) {
    if (!is_string($str)) {
      print "Erro: não é string";
      exit();
    }
    
    $str = trim(addslashes($str));
    
    $result = &$this->retrieve("SELECT article_id FROM article_settings WHERE setting_value LIKE '%$str%'");
    
    $returner = null;
    if ($result->RecordCount() != 0) {
      $returner = $result->GetRowAssoc(false);
    }
    
    $result->Close();
    unset($result);
    
    return $returner;
    
  }

  function _batchPeriodicosArticleId($articleId, $periodicoArtigoId, $debug = false) {

    if ($debug) {
      return "UPDATE periodicos_artigo SET article_id = " . $articleId . " WHERE periodico_artigo_id = " . $periodicoArtigoId;
    }

    $this->update('UPDATE periodicos_artigo SET article_id = ? WHERE periodico_artigo_id = ?', 
		  array($articleId,
			$periodicoArtigoId));



  }
  
  
  function _limpa($tableName) {
    
    $tables = array('periodicos', 'periodicos_artigo');
    if (in_array($tableName, $tables)) {
      //      $this->update("DELETE FROM $tableName");
    }
  }


  function periodicoNomeExists($nome, $journalId) {
    if (is_string($nome) && is_numeric($journalId)) {
      $result = &$this->retrieve("SELECT periodico_id FROM periodicos WHERE nome = ? AND journal_id = ?", array($nome, (int) $journalId));
    }
    
    $returner = null;
    if ($result->RecordCount() != 0) {
      $returner = $result->GetRowAssoc(false);
    }
    
    $result->Close();
    unset($result);
    
    return $returner;
    
  }


  function getPeriodicoEdicao($periodicoEdicaoId = '', $periodicoId = '', $issueId = '') {
    
    if ($periodicoEdicaoId != '') {
      $result = $this->retrieve("SELECT pe.*, p.nome FROM periodicos_edicao pe LEFT JOIN periodicos p ON pe.periodico_id = p.periodico_id WHERE pe.periodico_edicao_id = ?", array($periodicoEdicaoId));
    } else if ($periodicoEdicaoId == '' && ($periodicoId != '' && $issueId != '')) {
      if (is_numeric($periodicoId) && is_numeric($issueId)) {
	$result = $this->retrieve("SELECT * FROM periodicos_edicao WHERE periodico_id = ? AND issue_id =?", array($periodicoId, $issueId));
      }
    }
    
    $returner = null;
    if ($result->RecordCount() != 0) {
      $returner = $result->GetRowAssoc(false);
    }
    
    $result->Close();
    unset($result);
    
    return $returner;
  }


  function getPeriodicoArtigo($periodicoArtigoId) {
    
    $result = $this->retrieve("SELECT 
                                 pa.*,
                                 pe.issue_id, 
                                 pe.periodico_edicao_id, 
                                 pe.edicao, 
                                 pe.periodico_id, 
                                 p.nome AS periodico 
                               FROM periodicos_artigo pa
                                 LEFT JOIN periodicos_edicao pe ON pe.periodico_edicao_id = pa.periodico_edicao_id 
                                 LEFT JOIN periodicos p ON pe.periodico_id = p.periodico_id
                               WHERE pa.periodico_artigo_id = ?", array($periodicoArtigoId));
    
    $returner = null;
    if ($result->RecordCount() != 0) {
      $returner = $result->GetRowAssoc(false);
    }
    
    $result->Close();
    unset($result);
    
    return $returner;
  }
  
  
  function insertPeriodico(&$periodico) {

    $this->update('INSERT INTO periodicos
				(journal_id, nome, link, local, issn, acesso)
				VALUES
				(?, ?, ?, ?, ?, ?)',
		  array(
			$periodico->getJournalId(),
			$periodico->getNome(),
			$periodico->getLink(),
			$periodico->getLocal(),
			$periodico->getIssn(),
			$periodico->getAcesso())
		  );
    $result = $this->retrieve("SELECT last_value FROM periodicos_periodico_id_seq1");

    $returner = null;
    if ($result->RecordCount() != 0) {
      $returner = $result->GetRowAssoc(false);
    }

    $result->Close();

    return $returner['last_value'];
  }
  
  function deletePeriodico($periodicoId) {
    $this->update('DELETE FROM periodicos WHERE periodico_id = ?', array($periodicoId));
  }

  function deletePeriodicoEdicao($periodicoEdicaoId) {
    $this->update('DELETE FROM periodicos_edicao WHERE periodico_edicao_id = ?', array($periodicoEdicaoId));
  }

  function deletePeriodicoArtigo($periodicoArtigoId) {
    $this->update('DELETE FROM periodicos_artigo WHERE periodico_artigo_id = ?', array($periodicoArtigoId));
  }

  function updatePeriodico($periodico) {
    $this->update("UPDATE periodicos SET journal_id = ?, nome = ?, acesso = ?, issn = ?, local = ?, link = ? WHERE periodico_id = ?",
		  array($periodico->getJournalId(),
			$periodico->getNome(),
			$periodico->getAcesso(),
			$periodico->getIssn(),
			$periodico->getLocal(),
			$periodico->getLink(),
			$periodico->getPeriodicoId()
			));
  }


  function updatePeriodicoEdicao($periodicoEdicao) {
    
    $this->update("UPDATE periodicos_edicao SET edicao = ?, referencia = ?, responsavel = ?, periodico_id = ?, issue_id = ? WHERE periodico_edicao_id = ?",   
		  array($periodicoEdicao['edicao'],
			$periodicoEdicao['referencia'],
			$periodicoEdicao['responsavel'],
			$periodicoEdicao['periodico_id'],
			$periodicoEdicao['issue_id'],
			$periodicoEdicao['periodico_edicao_id']
			));
  }

  function updatePeriodicoArtigo($periodicoArtigo) {
    
    $this->update("UPDATE periodicos_artigo SET referencia = ? WHERE periodico_artigo_id = ?",   
		  array($periodicoArtigo['referencia'],
			$periodicoArtigo['periodico_artigo_id']
			));
  }

  



  //
  function updateGeneric($table, $field, $value, $cond_key, $cond_value) {
    $tables = array('periodicos', 'periodicos_edicao', 'periodicos_artigo');
    
    if (in_array($table, $tables)) {
      //print("UPDATE $table SET $field = '$value' WHERE $cond_key = '$cond_value';\n");
      $this->update("UPDATE $table SET $field = '?' WHERE $cond_key = '?'", array($value, $cond_value));
      
    } else {
      return false;
    }
  }

  
  function insertPeriodicoEdicao(&$periodicoEdicao) {
    $this->update('INSERT INTO periodicos_edicao
			(issue_id, edicao, periodico_id, responsavel)
				VALUES
				(?, ?, ?, ?)',
		  array($periodicoEdicao['issue_id'],
			$periodicoEdicao['edicao'],
			$periodicoEdicao['periodico_id'],
			$periodicoEdicao['responsavel'])
		  );
   
    return  $this->getInsertId('periodicos_edicao', 'periodico_edicao_id');
  }


  function insertPeriodicoArtigo(&$periodicoArtigo) {
    
    $this->update('INSERT INTO periodicos_artigo
				(periodico_edicao_id, article_id, referencia)
				VALUES
				(?, ?, ?)',
		  array($periodicoArtigo['periodico_edicao_id'],
			$periodicoArtigo['article_id'],
			$periodicoArtigo['referencia']
			)
		  );
    return  $this->getInsertId('periodicos_artigo', 'periodico_artigo_id');
  }

  function _batchPeriodicos() {
    $result = $this->retrieve("SELECT periodico_artigo_id, referencia FROM periodicos_artigo");

    $referencias = array();
    
    while (!$result->EOF) {
      $referencias[] =& $result->GetRowAssoc(false);
      $result->MoveNext();
    }
    
    $result->Close();
    unset($result);
    
    return $referencias;
  }


}

?>
