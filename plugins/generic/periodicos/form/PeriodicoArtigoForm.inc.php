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
//import('plugins/generic/periodicos/PeriodicoArtigo');
import('article/Article');
import('article/PublishedArticle');

class PeriodicoArtigoForm extends Form {

	/**
	 * Constructor.
	 */
	function PeriodicoArtigoForm($template) {
		parent::Form($template);
		$this->addCheck(new FormValidatorPost($this));
	}
	
	
	/**
	 * Validate the form
	 */
	function validate() {
		if ($this->getData('referencia')) {
			$this->addCheck(new FormValidatorLocale($this, 'referencia', 'required', 'periodico.referencia'));
		}
		// check if public issue ID has already used
		$journal =& Request::getJournal();
		$periodicosDao =& DAORegistry::getDAO('PeriodicosDAO');
		
		return parent::validate();
	}
       
	
	function readInputData() {
	  $this->readUserVars(array(
				    'referencia',
				    'periodico_artigo_id',
				    'periodico_edicao_id',
				    'article_id',
				    'issue_id',
				    'abstract',
				    'palavraschave',
				    'keywords'
				    ));
	}

	/**
	 * Save issue settings.
	 * 
	 * - inserts periodico, article and related
	 */
	function execute($periodicoArtigoId = 0) {
		$periodicosDao =& DAORegistry::getDAO('PeriodicosDAO');
		$articleDao =& DAORegistry::getDAO('ArticleDAO');
		$publishedArticleDao =& DAORegistry::getDAO('PublishedArticleDAO');
		$articleGalleyDao =& DAORegistry::getDAO('ArticleGalleyDAO');
		$articleFileDao =& DAORegistry::getDAO('ArticleFileDAO');
		$sectionDao =& DAORegistry::getDAO('SectionDAO');
		
		if ($periodicoArtigoId) {
		  $periodicoArtigo = $periodicosDao->getPeriodicoArtigo($periodicoArtigoId);
		  $isNewPeriodico = false;
		} else {
		  $periodicoArtigo = array();
		  $isNew = true;
		}
		
		$abstract = $this->getData('abstract');		
		$keywords = $this->getData('keywords');
		$palavraschave = $this->getData('palavraschave');

		$referencia = $this->getData('referencia');
		$article_id = $this->getData('article_id');	
		$periodicoEdicaoId = $this->getData('periodico_edicao_id');
		$periodicoArtigoId = $this->getData('periodico_artigo_id');
		$issue_id = $this->getData('issue_id');
		$section_id = 29;
		
		
		// se nao existir, cria article
		if ($article_id != 0) {
		  // update
		  $article = $articleDao->getArticle($article_id);
		  
		} else {
		  // insert
		  // - define objetos novos
		  // - preenche dados e valores default
		  $filePathPDF = "plugins/generic/periodicos/x.pdf";
		  
		  // Article
		  $article = &new Article();
		  $article->setJournalId(1, null);
		  $article->setSectionId($section_id, null);    // almanack: pode[ria] ser 5 tbm... :/ [numeracao bizonha a atual!]
		  $article->setUserId(1);
		  
		  $article->setDateSubmitted(Core::getCurrentDate());
		  $article->setDateStatusModified($now, null);
		  $article->setLastModified($now, null);		  
		  $article->setLanguage('pt_BR', null);
		  $article->setStatus(1);
		  $article->setData($now, null);
		  $article->setShowCoverPage(array('pt_BR' => 1), null);
		  
		  
		  // Article
		  $articleGalley = &new ArticleGalley();
		  $articleGalley->setFileId($article->getArticleId(), null);
		  $articleGalley->setFileName($filePathPDF, null);
		  $articleGalley->setOriginalFileName($filePathPDF, null);
		  $articleGalley->setFileType('application/pdf', null);
		  $articleGalley->setType('public', null);
		  $articleGalley->setDateModified(Core::getCurrentDate(), null);
		  $articleGalley->setFileSize($filesize, null);
		  $articleGalley->setRound(1, null);
		  
		  if (file_exists($filePathPDF)) {
		    $filesize = filesize($filePathPDF);
		  } else {
		    $filesize = 0;
		    print "[erro] - importacao do arquivo: ";
		    print $article->getTitle('pt_BR') . "\n" . $filePathPDF . "\n------------\n";
		    exit();
		  }
		  $articleGalley->setFileSize($filesize, null);
		  
		  if ($filesize == 0) {
		    print "não encontrado: $filePathPDF\n";exit();
		  }
		  
		  
		  // ArticleGalley
		  // - (arquivos pdf) - copio arquivo dummy para o article
		  $articleGalley->setLocale('pt_BR', null);	  
		  $articleGalley->setFileName($filePathPDF, null);
		  $articleGalley->setLabel('PDF', null);
		  
		  // PublishedArticle
		  $publishedArticle = new PublishedArticle();
		  $publishedArticle->setIssueId($issue_id);
		  $publishedArticle->setAccessStatus(1);
		  $publishedArticle->setSeq(REALLY_BIG_NUMBER);
		  $publishedArticle->setViews(0);
		  // $publishedArticle->setPublicArticleId($articleNode->getAttribute('public_id'));
		  $publishedArticle->setDatePublished(Core::getCurrentDate());		  
		  
		  
		  $author = new Author();
		  $author->setAuthorId($authorId, null);
		  
		  
		  $re_citation = '/^([A-Z]{1,}?)\,\s*([a-zA-Z\s]*)\./';
		  preg_match($re_citation, $referencia, $matches);
		  if (!empty($matches)) {
		    $firstName = $matches[2];
		    $middleName = '';
		    $lastName = ucfirst(strtolower($matches[1]));
		  } else {
		    $firstName = '';
		    $middleName = '';
		    $lastName = '';		    
		  }
		  
		  $author->setFirstName($firstName, null);
		  $author->setMiddleName('', null);
		  $author->setLastName($lastName, null);
		  
		  $author->setCountry('BR', null);
		  $author->setAffiliation('', null);
		  $author->setCountry('BR', null);
		  $author->setEmail('email@email.com', null);
		  $author->setUrl('', null);
		  $author->setCompetingInterests(array('pt_BR' => ''), null);
		  $author->setBiography(array('pt_BR' => ''), null);
		  $author->setPrimaryContact(1, null);
		}
		
		
		// variaveis padrao que podem mudar (insert / update)
		
		
		// TODO: locale
		$article->setAbstract($abstract, 'pt_BR');
		$article->setAbstract($abstract, 'en_US');
		$article->setSubject($palavraschave, 'pt_BR');
		$article->setSubject($keywords, 'en_US');
		
		$article->setTitle($referencia, 'pt_BR');
		$article->setTitle($referencia, 'en_US');
		
		
		// periodicoArtigo
		$periodicoArtigo['referencia'] = $referencia;
		$periodicoArtigo['periodico_edicao_id'] = $periodicoEdicaoId;
		
		if ($periodicoArtigoId != 0) {
		  
		  // UPDATE	  
		  $articleDao->updateArticle(&$article);
		  $periodicoArtigo['periodico_artigo_id'] = $periodicoArtigoId;
		  $periodicosDao->updatePeriodicoArtigo($periodicoArtigo);
		} else {
		  
		  // INSERT
		  
		  // insere itens do article	  
		  $author->setSequence($authorId, null);
		  $authorId = $article->addAuthor($author);
		  $author->setAuthorId($authorId);
		  
		  $articleId = $articleDao->insertArticle(&$article);
		  $article->setArticleId($articleId);
		  
		  $publishedArticle->setArticleId($article->getArticleId());  
		  $publishedArticle->setPubId($publishedArticleDao->insertPublishedArticle($publishedArticle));
		  $publishedArticleDao->resequencePublishedArticles($section_id, $issue_id);
		  
		  import('file.ArticleFileManager');
		  $articleFileManager = new ArticleFileManager($article->getArticleId());	  
		  $fileId = $articleFileManager->copyPublicFile($filePathPDF, 'application/pdf');		  
		  $articleGalley->setArticleId($article->getArticleId());
		  $articleGalley->setFileId($fileId);
		  $articleGalleyDao->insertGalley($articleGalley);
		  
		  // insere do periodico
		  $periodicoArtigo['article_id'] = $article->getArticleId();
		  $periodicoArtigoId = $periodicosDao->insertPeriodicoArtigo($periodicoArtigo);
		}	
		
		return $periodicoArtigoId;
	}
}

?>
