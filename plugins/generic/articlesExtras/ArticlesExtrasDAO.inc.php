<?php

/**
 * @file ArticlesExtrasDAO.php
 *
 * Copyright (c) 2009 Richard González Alberto
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_articlesExtras
 * @brief Articles Extras DAO class.
 *
 */

class ArticlesExtrasDAO extends DAO {

	/**
	 * Constructor.
	 */
	function ArticlesExtrasDAO() {
		parent::DAO();
	}
	
	/**
	 * Sets article body.
	 */
	function setArticleBody(&$article, $body){
		if(!$this->settingExists($article->getArticleId(), "body"))
			$this->insertArticleBody($article, $body);
		else
			$this->updateArticleBody($article, $body);
	}
	
	/**
	 * Private function inserts a new article body record.
	 */
	function insertArticleBody(&$article, $body) {
		$primaryLocale = Localex::getPrimaryLocale();
		$this->update(
			sprintf('INSERT INTO article_settings
				(
					article_id,
					locale,
					setting_name,
					setting_value,
					setting_type
				)
				VALUES 
				(%s, \'%s\', \'%s\', \'%s\', \'%s\')',
				$article->getArticleId(),
				$primaryLocale,
				"body",
				$body,
				"string"
			)
	 	);
	}
	
	/**
	 * Private function updates an existing article body
	 */
	function updateArticleBody(&$article, $body) {
		$primaryLocale = Localex::getPrimaryLocale();
		$this->update(
			sprintf('UPDATE article_settings SET 
					setting_value = \'%s\' 
					WHERE article_id = %s AND 
					setting_name=\'body\' AND 
					locale=\'%s\'', 
					$body, 
					$article->getArticleId(), 
					$primaryLocale
				)
	 	);
	}
	
	/**
	 * Checks if a given setting exists for an specific article
	 */
	function settingExists($articleId, $settingName, $locale = null){
		if($locale == null) $locale = Localex::getPrimaryLocale();

		$result = &$this->retrieve('SELECT setting_value FROM article_settings WHERE 
									setting_name = ? AND 
									article_id = ? AND 
									locale = ?', 
									array($settingName, $articleId, $locale)
								   );

		if ($result->RecordCount() != 0) {
			return true;
		}
		else return false;
	}
	
	/**
	 * Returns article body.
	 */
	function getArticleBody($articleId) {
		$primaryLocale = Localex::getPrimaryLocale();

		$result = &$this->retrieve('SELECT setting_value FROM article_settings WHERE 
									setting_name=\'body\' AND 
									article_id = ? AND 
									locale = ? ', 
									array($articleId, $primaryLocale));
		
		return $result->fields['setting_value'];
	}
	
	/**
	 * Returns article images
	 */
	function getArticleImages($articleId) {
		$primaryLocale = Localex::getPrimaryLocale();

		$result = &$this->retrieve('SELECT setting_value FROM article_settings WHERE 
									setting_name=\'images\' AND 
									article_id = ? AND 
									locale = ? ', 
									array($articleId, $primaryLocale));
		
		return $result->fields['setting_value'];
	}
	
	/**
	 * Sets article images
	 */
	function setArticleImages(&$article, $images)	{
		if(!$this->settingExists($article->getArticleId(), "images"))
			$this->insertArticleImages($article, $images);
		else
			$this->updateArticleImages($article, $images);
	}
	
	/**
	 * Private function inserts a new set of article images
	 */
	function insertArticleImages(&$article, $images) {
		$primaryLocale = Localex::getPrimaryLocale();
		$this->update(
			sprintf('INSERT INTO article_settings
				(
					article_id,
					locale,
					setting_name,
					setting_value,
					setting_type
				)
				VALUES 
				(%s, \'%s\', \'%s\', \'%s\', \'%s\')',
				$article->getArticleId(),
				$primaryLocale,
				"images",
				$images,
				"string"
			)
	 	);
	}
	
	/**
	 * Private function updates an existing set of article images
	 */
	function updateArticleImages(&$article, $images) {
		$primaryLocale = Localex::getPrimaryLocale();
		$this->update(
			sprintf('UPDATE article_settings SET 
					setting_value = \'%s\' 
					WHERE article_id = %s AND 
					setting_name=\'images\' AND 
					locale=\'%s\'', 
					$images, 
					$article->getArticleId(), 
					$primaryLocale
				)
	 	);
	}
	
	/**
	 * Returns a the number of images for the given articleId
	 */
	function countImagesByArticleId($articleId){
		$locale = Localex::getPrimaryLocale();

		$result = &$this->retrieve('SELECT setting_value FROM article_settings WHERE 
									setting_name = ? AND 
									article_id = ? AND 
									locale = ?', 
									array("images", $articleId, $locale)
								   );
		
		//just for test make actual count
		return $result->RecordCount();
	}
	
	/**
	 * Sets article citations
	 */
	function setArticleCitations($articleId, $citations)	{
		if(!$this->settingExists($articleId, "citations"))
			$this->insertArticleCitations($articleId, $citations);
		else
			$this->updateArticleCitations($articleId, $citations);
	}	 
	
	/**
	 * Private function insert a new set of article citations
	 */
	function insertArticleCitations($articleId, $citations) {
		$primaryLocale = Localex::getPrimaryLocale();
		$serialCitations = serialize($citations);
		$this->update(
			sprintf('INSERT INTO article_settings
				(
					article_id,
					locale,
					setting_name,
					setting_value,
					setting_type
				)
				VALUES 
				(%s, \'%s\', \'%s\', \'%s\', \'%s\')',
				$articleId,
				$primaryLocale,
				"citations",
				$serialCitations,
				"string"
			)
	 	);
	}
	
	/**
	 * Private function updates an existing set of article citations
	 */
	 function updateArticleCitations($articleId, $citations) {
		$primaryLocale = Localex::getPrimaryLocale();
		$serialCitations = serialize($citations);
		$this->update(
			sprintf('UPDATE article_settings SET 
					 setting_value = \'%s\' WHERE 
					 article_id = %s AND 
					 setting_name=\'citations\' AND 
					 locale=\'%s\'', 
					 $serialCitations, 
					 $articleId, 
					 $primaryLocale
					)
	 	);
	}
	
	/**
	 * Returns a serialized string of citations for the given articleId
	 */
	function getCitationsByArticleId($articleId){
		$primaryLocale = Localex::getPrimaryLocale();
		
		$result = &$this->retrieve('SELECT setting_value FROM article_settings WHERE 
									setting_name=? AND 
									article_id = ? AND 
									locale = ?', 
									array("citations", $articleId, $primaryLocale)
									);
		
		return $result->fields['setting_value'];	
	}
	
}
?>