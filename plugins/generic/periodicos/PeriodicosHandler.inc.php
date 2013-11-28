<?php

/**
 * @file PeriodicosHandler.inc.php
 *
 * Copyright (c) 2010 Fernão Lopes
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PeriodicosHandler
 * @ingroup plugins_generic_periodicos
 *
 * @brief Plugin para periódicos Almanack
 */

// $Id: CounterHandler.inc.php,v 1.7.2.2 2009/04/08 19:43:15 asmecher Exp $

import('handler.Handler');
import('plugins/generic/periodicos/Periodico');
import('article/Article');
import('article/PublishedArticle');

class PeriodicosHandler extends Handler {
  
  function index($args = null) {
    
    /*
      paginas:
      - periodicos/view/issue/10/5
      - periodicos/view/periodico/5
     */
  }

  function periodico($args) {

   // para ver infos direto por periodico
    list($plugin) = PeriodicosHandler::validate();
    
    $periodicoId = isset($args[1]) ? $args[1] : '';
    
    // puxar dados gerais
    $journal = &Request::getJournal();
    
    
    // criar pagina da listagem de artigos por periódico daquela edicao
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');
    
    $periodicos = $periodicosDao->_getArtigosFromPeriodico($periodicoId);
    
    // ? criar pagina com periódicos já tratados (futuro)
    $templateManager =& TemplateManager::getManager();
    
    $templateManager->assign('periodicos', $periodicos);    
    $templateManager->display($plugin->getTemplatePath() . 'por_periodico.tpl');
  }

  function issue($args) {
    
    // para ver infos de periodicos por issue
    
    list($plugin) = PeriodicosHandler::validate(true, true);
    
    $issueId = isset($args[0]) ? $args[0] : 0;
    $periodicoId = isset($args[1]) ? $args[1] : '';
    
    if (!$issueId || !$periodicoId) {
      exit();
    }
    // puxar dados gerais
    $journal = &Request::getJournal();
    
    $issueDao = &DAORegistry::getDAO('IssueDAO');
    $issue = &$issueDao->getIssueById($issueId, $journal->getJournalId());
    
    $articleDao = &DAORegistry::getDAO('ArticleDAO');
    
    
    // criar pagina da listagem de artigos por periódico daquela edicao
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');
    
    $periodicosArtigosResult = $periodicosDao->_getArtigosFromPeriodico($periodicoId, $issueId);
    $periodicosArtigos = array();

    $periodicosEdicoes = array();
    
    foreach ($periodicosArtigosResult as $artigo) {

      $artigo['article'] = $articleDao->getArticle($artigo['article_id']);
      $periodicosArtigos[$artigo['edicao']][] = $artigo;
      $periodicosEdicoes[$artigo['edicao']] = $artigo['edicao'];
    }
    
    
    // pegar lista de periodicos para fazer menu de revistas
    $periodicosResult = $periodicosDao->_getPeriodicosFromIssue($issueId, true);
    $outrosPeriodicos = array();
    foreach($periodicosResult as $periodico) {
      $outrosPeriodicos[] = $periodico;
    }
    
    $periodicoTmp = $periodicosArtigos[$artigo['edicao']][0];

    $periodicoAtual = $periodicosDao->_getPeriodicoFrom('periodico_id', $periodicoTmp['periodico_id']);
    $periodicoAtual['responsavel'] = $periodicoTmp['responsavel'];
    $periodicoAtual['link'] = $periodicoTmp['link'];
    $periodicoAtual['edicao'] = $periodicoTmp['edicao'];
    
    // ? criar pagina com periódicos que ja foram resenhados alguma vez (futuro) - (pagina do periodico)
    
    $templateManager =& TemplateManager::getManager();

    $pdfPeriodicos = "files/journals/1/periodicos/$issueId.pdf";
    if (file_exists($pdfPeriodicos)) {
      $templateManager->assign('pdfPeriodicos', $pdfPeriodicos);
    }
    
    $templateManager->assign('pageHierarchy', array(array(Request::url(null, 'issue', 'archive'),'archive.archives'),
						    array(Request::url(null, 'issue', 'view', array($issueId, "showToc")), $issue->getIssueIdentification(false, true), true)
						    ));
    $templateManager->assign('pageTitleTranslated', Localex::translate('plugins.generic.periodicos'));
    $templateManager->assign('issue', $issue);
    $templateManager->assign('edicoes', $periodicosEdicoes);
    $templateManager->assign('periodicoAtual', $periodicoAtual);
    $templateManager->assign('outrosPeriodicos', $outrosPeriodicos);
    $templateManager->assign('periodicosArtigos', $periodicosArtigos);
    $templateManager->display($plugin->getTemplatePath() . 'por_issue.tpl');
  }



  /******
   *
   * funcoes administrativas
   *
   *****/

  function createPeriodico($args) {
    list($plugin) = PeriodicosHandler::validate();
    
    $task = isset($args[0]) ? $args[0] : 0;
    
    // TODO:
    // verificacao de login!
    
    // exibir formulario
    // gerenciar envio
    // listagem de periodicos ?    
    $periodico = "";
    
    $templateManager =& TemplateManager::getManager();
    $templateManager->assign('periodicos', $periodico);
    $templateManager->assign('pageTitleTranslated', Localex::translate('plugins.generic.periodicos'));    
    $templateManager->assign('action', 'criar'); 
    $templateManager->assign('actionForm', 'savePeriodico');
    $templateManager->display($plugin->getTemplatePath() . 'editPeriodicos.tpl');
  }  

  
  function uploadPdf($issueId) {
    list($plugin) = PeriodicosHandler::validate();
    
    $pdf = $_FILES['pdf'];
    if ($pdf['error'] == 0) {
      
      $tmp_name = $pdf['tmp_name'];
      $name = "files/journals/1/periodicos/$issueId.pdf";
      
      move_uploaded_file($tmp_name, $name);     
    }
    
    return $name;
  }
  
  function manage($args) {
    list($plugin) = PeriodicosHandler::validate();
    $issueId = isset($args[0]) ? $args[0] : null;
    
    // se fizer upload
    if (!empty($_FILES['pdf'])) {
      $pdfPeriodicos = $this->uploadPdf($issueId);
    } else {
      $pdfPeriodicos = false;
    }
    
    if (!$pdfPeriodicos) {
      $name = "files/journals/1/periodicos/$issueId.pdf";
      if (file_exists($name)) {
	$pdfPeriodicos = $name;
      }
    }
    
    // dados gerais
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');

    $journal = &Request::getJournal();
    $issueDao = &DAORegistry::getDAO('IssueDAO');
    $issue = $issueDao->getIssueById($issueId, $journal->getJournalId());
    
    $listaPeriodicosIssueResult = $periodicosDao->_getPeriodicosFromIssue($issueId);
    $listaPeriodicosFullResult = $periodicosDao->_getPeriodicos('nome');
    
    $listaPeriodicosFull = array();
    foreach($listaPeriodicosFullResult as $periodicoList) {
      $listaPeriodicosFull[$periodicoList['periodico_id']] = $periodicoList['nome'];
    }
    
    $listaPeriodicosIssue = array();
    $listaPeriodicosComp = array();
    
    foreach($listaPeriodicosIssueResult as $periodicosIssue) {
      $listaPeriodicosComp[$periodicosIssue['periodico_id']] = $periodicosIssue['nome'];

      if (!array_key_exists($periodicosIssue['periodico_id'], $listaPeriodicosIssue)) {
	$listaPeriodicosIssue[$periodicosIssue['periodico_id']] = array();
	$listaPeriodicosIssue[$periodicosIssue['periodico_id']]['nome'] = $periodicosIssue['nome'];
      }
      
      $listaPeriodicosIssue[$periodicosIssue['periodico_id']][$periodicosIssue['periodico_edicao_id']] = $periodicosIssue['edicao'];
    }
    
    $listaPeriodicosFull = array_diff($listaPeriodicosFull, $listaPeriodicosComp);
    
    $templateManager =& TemplateManager::getManager();
    $templateManager->assign('pageTitleTranslated', Localex::translate('plugins.generic.periodicos.admin.manage_periodicos'));
    //    $templateManager->assign('periodicos', $periodicos);
    $templateManager->assign('issueId', $issueId);
    $templateManager->assign('pdfPeriodicos', $pdfPeriodicos);
    $templateManager->assign('listaPeriodicosFull', $listaPeriodicosFull);
    $templateManager->assign('listaPeriodicosIssue', $listaPeriodicosIssue);
    //    $templateManager->assign('actionForm', 'manage');
    $isLayoutEditor = false;  // p/ para de cuspir erros
    $pageHierarchy = array(
			   array(Request::url(null, 'user'), 'navigation.user'), 
			   array(Request::url(null, $isLayoutEditor?'layoutEditor':'editor'), $isLayoutEditor?'user.role.layoutEditor':'user.role.editor'),
			   array(Request::url(null, $isLayoutEditor?'layoutEditor':'editor', 'futureIssues'), 'issue.issues'),
			   array(Request::url(null, 'editor', 'issueToc', array($issueId)), $issue->getIssueIdentification(false, true), true)
			   );
    $templateManager->assign('pageHierarchy', $pageHierarchy);
    
    $templateManager->display($plugin->getTemplatePath() . 'manage.tpl');    
  }

  
  function editPeriodico($args) {
    list($plugin) = PeriodicosHandler::validate();
    
    $periodicoId = isset($args[0]) ? $args[0] : null;
    // TODO : 
    // verificacao de login!
    
    // puxar dados gerais
    $journal = &Request::getJournal();
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');
    
    
    $periodico = $periodicosDao->_getPeriodicoFrom('periodico_id', $periodicoId);

    $templateManager =& TemplateManager::getManager();
    $templateManager->assign('action', 'editar'); 
    $templateManager->assign('actionForm', 'savePeriodico');
    $templateManager->assign('periodico', $periodico);
    $templateManager->assign('pageHierarchy', array(
						    array(Request::url(null, 'user'),'navigation.user'),
						    array(Request::url(null, 'editor'),'user.role.editor'),
						    array(Request::url(null, 'periodicos','listPeriodicos', array('nome')), 'plugins.generic.periodicos.admin.listar_periodicos'),
						    array(Request::url(null, 'user'),'navigation.user')
						    ));
    
    $templateManager->assign('pageTitleTranslated', Localex::translate('plugins.generic.periodicos.admin.editar_periodicos'));
    $templateManager->display($plugin->getTemplatePath() . 'editPeriodicos.tpl');   

  }

  
  function editPeriodicoEdicao($args) {
    list($plugin) = PeriodicosHandler::validate();
    
    $issueId = isset($args[0]) ? $args[0] : null;
    $periodicoEdicaoId = isset($args[1]) ? $args[1] : null;
    $periodicoId = isset($args[2]) ? $args[2] : 0;
    
    //    print($issueId . "/" . $periodicoEdicaoId . "/" . $periodicoId);
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');

    if ($periodicoEdicaoId || $periodicoEdicaoId != 0) {
      $periodicoEdicao = $periodicosDao->getPeriodicoEdicao($periodicoEdicaoId);
    } else {
      $periodicoEdicao = array('issue_id' => $issueId,
			       'periodico_id' => $periodicoId
			       );
    }
  
require_once("listaResponsaveis.php");  
//    $listaResponsaveis = array('Marina Garcia de Oliveira' => 'Marina Garcia de Oliveira',
//			       'Indara Mayer' => 'Indara Mayer',
//			       'Ana Priscilla Barbosa de Lucena' => 'Ana Priscilla Barbosa de Lucena');
    
    
    $journal = &Request::getJournal();
    $issueDao = &DAORegistry::getDAO('IssueDAO');
    $issue = $issueDao->getIssueById($issueId, $journal->getJournalId());
    
    $periodico = $periodicosDao->getPeriodico($periodicoEdicao['periodico_id']);
    $artigosPeriodicoEdicao =  $periodicosDao->_getArtigosFromPeriodicoEdicao($periodicoEdicaoId);
    
    $templateManager =& TemplateManager::getManager();
    $templateManager->assign('pageTitleTranslated', Localex::translate('plugins.generic.periodicos.admin.editando.edicao') . " " . $periodicoEdicao['edicao']);
    $templateManager->assign('periodicoEdicao', $periodicoEdicao);
    $templateManager->assign('artigosPeriodicoEdicao', $artigosPeriodicoEdicao);
    $templateManager->assign('periodico', $periodico);
    $templateManager->assign('issueId', $issueId);
    $templateManager->assign('listaResponsaveis', $listaResponsaveis);
    $templateManager->assign('actionForm', 'savePeriodicoEdicao');  

    $isLayoutEditor = false;  // p/ para de cuspir erros    
    $pageHierarchy = array(
			   array(Request::url(null, 'user'), 'navigation.user'), 
			   array(Request::url(null, $isLayoutEditor?'layoutEditor':'editor'), $isLayoutEditor?'user.role.layoutEditor':'user.role.editor'),
			   array(Request::url(null, $isLayoutEditor?'layoutEditor':'editor', 'futureIssues'), 'issue.issues'),
			   array(Request::url(null, 'editor', 'issueToc', array($issueId)), $issue->getIssueIdentification(false, true), true),
			   array(Request::url(null, 'periodicos', 'manage', array($issueId)), 'plugins.generic.periodicos.admin.manage_periodicos'),
			   );
    $templateManager->assign('pageHierarchy', $pageHierarchy);
    
    $templateManager->display($plugin->getTemplatePath() . 'editPeriodicoEdicao.tpl');

  }
  
  
  function editPeriodicoArtigo($args) {
    list($plugin) = PeriodicosHandler::validate();
    
    $periodicoArtigoId = isset($args[0]) ? $args[0] : 0;  // req
    $periodicoEdicaoId = isset($args[1]) ? $args[1] : 0;  // req
    $issueId = isset($args[2]) ? $args[2] : 0;
    $periodicoId = isset($args[3]) ? $args[3] : 0;
    
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');
    
    // update - pega dados
    if ($periodicoArtigoId != 0) {
      
      $articleDao = &DAORegistry::getDAO('ArticleDAO');
      
      $periodicoArtigo = $periodicosDao->getPeriodicoArtigo($periodicoArtigoId);
      $article = $articleDao->getArticle($periodicoArtigo['article_id']);
      $periodicoEdicaoId = ($periodicoEdicaoId !=0) ? $periodicoEdicaoId : $periodicoArtigo['periodico_edicao_id'];
      
      // insert - pega dados via get
    } else {
      $periodicoArtigo['periodico_artigo_id'] = $periodicoArtigoId;
      
      $article = new PublishedArticle();
    }

    // pega dados da periodicoEdicao
    $periodicoEdicao = $periodicosDao->getPeriodicoEdicao($periodicoEdicaoId);
    
    $periodicoId = ($periodicoId != 0) ? $periodicoId : $periodicoEdicao['periodico_id'];
    $periodico = $periodicosDao->getPeriodico($periodicoId);
    
    $journal = &Request::getJournal();
    
    // issue
    $issueId = ($issueId != 0) ? $issueId : $periodicoArtigo['issue_id'];
    $issueDao = &DAORegistry::getDAO('IssueDAO');
    $issue = $issueDao->getIssueById($issueId, $journal->getJournalId());
    
    $templateManager =& TemplateManager::getManager();
    $templateManager->assign('pageTitleTranslated', Localex::translate('plugins.generic.periodicos.admin.editando.artigo'));
    $templateManager->assign('periodicoArtigo', $periodicoArtigo);
    $templateManager->assign('periodicoEdicao', $periodicoEdicao);
    $templateManager->assign('periodico', $periodico);
    $templateManager->assign('article', $article);
    $templateManager->assign('issueId', $issueId);
    $templateManager->assign('actionForm', 'savePeriodicoArtigo');  

    $isLayoutEditor = false;  // p/ para de cuspir erros    
    $pageHierarchy = array(
			   array(Request::url(null, 'user'), 'navigation.user'), 
			   array(Request::url(null, $isLayoutEditor?'layoutEditor':'editor'), $isLayoutEditor?'user.role.layoutEditor':'user.role.editor'),
			   array(Request::url(null, $isLayoutEditor?'layoutEditor':'editor', 'futureIssues'), 'issue.issues'),
			   array(Request::url(null, 'editor', 'issueToc', array($issueId)), $issue->getIssueIdentification(false, true), true),
			   array(Request::url(null, 'periodicos', 'manage', array($issueId)), 'plugins.generic.periodicos.admin.manage_periodicos'),
			   array(Request::url(null, 'periodicos', 'editPeriodicoEdicao', array($issueId, $periodicoEdicao['periodico_edicao_id'])),
				 Localex::translate('plugins.generic.periodicos.admin.editando.edicao') . " " . $periodicoArtigo['edicao'], true)
		   );
    
    $templateManager->assign('pageHierarchy', $pageHierarchy);
    
    $templateManager->display($plugin->getTemplatePath() . 'editPeriodicoArtigo.tpl');
    
  }


  function listarPeriodicos($args) {
    list($plugin) = PeriodicosHandler::validate();
    
    //$issueId = isset($args[0]) ? $args[0] : 0;
    $sortBy = isset($args[0]) ? $args[0] : null;
    $order = isset($args[1]) ? $args[1] : null;
    
    // TODO : 
    // verificacao de login!

    // listar periodicos
    
    // puxar dados gerais
    $journal = &Request::getJournal();
    
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');
    $periodicosResult = $periodicosDao->_getPeriodicos($sortBy. $order);
    $periodicos = array();
    
    foreach ($periodicosResult as $periodico) {
      $periodicos[] = $periodico;
    }
    
    $templateManager =& TemplateManager::getManager();
    
    $templateManager->assign('pageHierarchy', array(
						    array(Request::url(null, 'user'),'navigation.user'),
						    array(Request::url(null, 'editor'),'user.role.editor')));
    
    $templateManager->assign('pageTitleTranslated', Localex::translate('plugins.generic.periodicos.admin.listar_periodicos'));    
    $templateManager->assign('periodicos', $periodicos);
    $templateManager->display($plugin->getTemplatePath() . 'listPeriodicos.tpl');    
  }

  function savePeriodicoArtigo($args) {
    list($plugin) = PeriodicosHandler::validate();

    $periodicoArtigoId = isset($args[0]) ? $args[0] : null;
    
    $this->validate();
    $this->setupTemplate(EDITOR_SECTION_ISSUES);
    
    import('plugins.generic.periodicos.form.PeriodicoArtigoForm');
    if (checkPhpVersion('5.0.0')) { // WARNING: This form needs $this in constructor
      $periodicoArtigoForm = new PeriodicoArtigoForm($plugin->getTemplatePath() . 'editPeriodicoArtigo.tpl');
    } else {
      $periodicoArtigoForm = new PeriodicoArtigoForm($plugin->getTemplatePath() . 'editPeriodicoArtigo.tpl');
    }
    
    $periodicoArtigoForm->readInputData();    
    $periodicoArtigoId = $periodicoArtigoForm->execute($periodicoArtigoId);
    
    // pega dados para montar redirect
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');
    $periodicoEdicaoId = $periodicoArtigoForm->getData('periodico_edicao_id');
    $periodicoEdicao = $periodicosDao->getPeriodicoEdicao($periodicoEdicaoId);
    Request::redirect(null, 'periodicos', 'editPeriodicoArtigo', array($periodicoArtigoId, $periodicoEdicaoId, $periodicoEdicao['issue_id'], $periodicoEdicao['periodico']));    
  }


  function savePeriodicoEdicao($args) {
    list($plugin) = PeriodicosHandler::validate();
    $issueId = isset($args[0]) ? $args[0] : null;
    $periodicoEdicaoId = isset($args[1]) ? $args[1] : null;

    $this->validate();
    $this->setupTemplate(EDITOR_SECTION_ISSUES);
    
    import('plugins.generic.periodicos.form.PeriodicoEdicaoForm');
    if (checkPhpVersion('5.0.0')) { // WARNING: This form needs $this in constructor
      $periodicoEdicaoForm = new PeriodicoEdicaoForm($plugin->getTemplatePath() . 'editPeriodico.tpl');
    } else {
      $periodicoEdicaoForm = new PeriodicoEdicaoForm($plugin->getTemplatePath() . 'editPeriodico.tpl');
    }
    
    $periodicoEdicaoForm->readInputData();
    
    // TODO: validate
    $periodicoEdicaoId = $periodicoEdicaoForm->execute($periodicoEdicaoId);
    Request::redirect(null, 'periodicos', 'editPeriodicoEdicao', array($issueId, $periodicoEdicaoId));
  }

  function savePeriodico($args) {
    list($plugin) = PeriodicosHandler::validate();
    $periodicoId = isset($args[0]) ? $args[0] : null;
    
    if ($periodicoId == 'novo') {
      $periodicoId ==  '';
    }
    
    $this->validate();
    $this->setupTemplate(EDITOR_SECTION_ISSUES);
    
    import('plugins.generic.periodicos.form.PeriodicoForm');
    if (checkPhpVersion('5.0.0')) { // WARNING: This form needs $this in constructor
      $periodicoForm = new PeriodicoForm($plugin->getTemplatePath() . 'editPeriodico.tpl');
    } else {
      $periodicoForm = new PeriodicoForm($plugin->getTemplatePath() . 'editPeriodico.tpl');
    }
    
    $periodicoForm->readInputData();
    
    // TODO: validate
    $periodicoId = $periodicoForm->execute();

    Request::redirect(null, 'periodicos', 'editPeriodico', $periodicoId);
  }

  function deletePeriodico($args) {
    list($plugin) = PeriodicosHandler::validate();
    $periodicoId = isset($args[0]) ? $args[0] : null;
    
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');
    $periodico = $periodicosDao->deletePeriodico($periodicoId);
    
    Request::redirect(null, 'periodicos', 'listarPeriodicos', 'nome');
  }

  function deletePeriodicoEdicao($args) {
    list($plugin) = PeriodicosHandler::validate();
    $periodicoEdicaoId = isset($args[0]) ? $args[0] : null;
    
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');
    $tmpPeriodico = $periodicosDao->getPeriodicoEdicao($periodicoEdicaoId);
    $periodico = $periodicosDao->deletePeriodicoEdicao($periodicoEdicaoId);
    
    Request::redirect(null, 'periodicos', 'manage', $tmpPeriodico['issue_id']);
  }

  function deletePeriodicoArtigo($args) {
    list($plugin) = PeriodicosHandler::validate();
    $periodicoArtigoId = isset($args[0]) ? $args[0] : null;
    
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');
    $tmpPeriodico = $periodicosDao->getPeriodicoArtigo($periodicoArtigoId);
    $periodico = $periodicosDao->deletePeriodicoArtigo($periodicoArtigoId);
    
    $articleDao = &DAORegistry::getDAO('ArticleDAO');
    $delete = $articleDao->deleteArticleById($tmpPeriodico['article_id']);
    
    
    Request::redirect(null, 'periodicos', 'editPeriodicoEdicao', array($tmpPeriodico['issue_id'], $tmpPeriodico['periodico_edicao_id']));
  }


  function setupTemplate() {
  }

  function validate($canRedirect = true, $freeAccess = false) {
    parent::validate();
    
    $journal = &Request::getJournal();
    if (!Validation::isEditor($journal->getJournalId()) && !$freeAccess) {
      Validation::redirectLogin();
    }
    
    $plugin =& Registry::get('plugin');
    return array(&$plugin);
  }

  
  
  function batch() {
    $periodicosDao = &DAORegistry::getDAO('PeriodicosDAO');
    $referencias = $periodicosDao->_batchPeriodicos();
    
    foreach ($referencias as $referencia) {
      $re = '/(.*\n*.*)\n*\h-\h.*/';
      preg_match($re, $referencia['referencia'], $matches);

      //print "------------------------------------\n";
      //print $referencia['periodico_artigo_id'] . "\n";
      //print $referencia['referencia'] . "\n";
      //print_r($matches);
      if (!empty($matches)) {
	//print "(" . $matches[1] . ")\n";
	
	//$periodicosDao->updateGeneric('periodicos_artigo', 'referencia', trim($matches[1]), 'periodico_artigo_id', $referencia['periodico_artigo_id']);
      }
    }
  }
}
