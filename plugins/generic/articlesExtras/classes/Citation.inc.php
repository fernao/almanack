<?php
class Citation{
	// Citation data
	var $_data;
	
	// Citation locale
	var $locale;
	
	function Citation($data){
		$this->_data = $data;
		$this->locale = $data['selectLanguage'];
	}
	
	function getData(){
		return $this->_data;
	}
	
	function getLocale(){
		return $this->locale;
	}
	
	function getAuthors($includeOrgAuthors = true){
		$authors = false;
		if(isset($this->_data['authors'])){ 
			$authors = $this->_data['authors'];
		}
		if($includeOrgAuthors && isset($this->_data['author_organization'])){
			if(isset($this->_data['authors'])) $authors .= "; ";
			$authors .= $this->getOrganizationAuthor();	
		}
		
		return $authors;
	}
	
	function getOrganizationAuthor(){
		return isset($this->_data['author_organization']) ? $this->_data['author_organization'] : false;
	}
	
	function getTitle(){
		return isset($this->_data['title']) ? $this->_data['title'] : false;
	}
	
	function getSource(){
		return isset($this->_data['source']) ? $this->_data['source'] : false;
	}
	
	function hasDate(){
		if(isset($this->_data['year'])){
			return true;
		}
		return false;
	}
	
	function hasLastUpdateDate(){
		if(isset($this->_data['updYear'])){
			return true;
		}
		return false;
	}
	
	function hasCitationDate(){
		if(isset($this->_data['citYear'])){
			return true;
		}
		return false;
	}
	
	function hasWebsiteDate(){
		if(isset($this->_data['siteDateFrom'])){
			return true;
		}
		return false;
	}
	
	function hasRetractionDate(){
		if(isset($this->_data['retYear'])){
			return true;
		}
		return false;
	}
	
	function hasCorrectionDate(){
		if(isset($this->_data['corYear'])){
			return true;
		}
		return false;
	}
	
	function hasConferenceDate(){
		if(isset($this->_data['confYear'])){
			return true;
		}
		return false;
	}
	
	function getDate(){
		$months = $this->getMonths();								
		
		// Pub Date				
		if($this->hasDate()){
			$date  = $this->_data['year'];
			$date .= isset($this->_data['month']) ? " " . $months[$this->_data['month']] : "";
			$date .= isset($this->_data['day']) ? " " . $this->_data['day'] : "";
			
			return ltrim(rtrim($date));
		}
		elseif($this->getForthcomingDate()){
			return $this->getForthcomingDate();
		}
		elseif($this->hasWebsiteDate()){
			return $this->_data['siteDateFrom'];
		}
		
		return false;
	}
	function getConferenceDate(){
		$months = $this->getMonths();								
								
		if($this->hasConferenceDate()){
			$date  = $this->_data['confYear'];
			$date .= isset($this->_data['confMonth'])  ? " " . $months[$this->_data['confMonth']] : "";
			$date .= isset($this->_data['confDayFrom']) ? " " . $this->_data['confDayFrom'] : "";
			$date .= isset($this->_data['confDayTo']) && $this->_data['confDayTo'] != "" ? "-" . $this->_data['confDayTo'] : "";
			
			return ltrim(rtrim($date));
		}
		
		return false;
	}
	
	function getLastUpdateDate(){
		$months = $this->getMonths();								
								
		if($this->hasLastUpdateDate()){
			$date  = isset($this->_data['updDay']) ? $this->_data['updDay'] : "";
			$date .= isset($this->_data['updMonth']) ? " " . $months[$this->_data['updMonth']] : "";
			$date .= " " . $this->_data['updYear'];
			
			return ltrim(rtrim($date));
		}
		
		return false;
	}
	
	function getWebsiteDate(){								
		if($this->hasWebsiteDate()){
			$date  = $this->_data['siteDateFrom'];
			$date .= $this->_data['siteDateTo'] != "" ? "-" . $this->_data['siteDateTo'] : "";
			
			return ltrim(rtrim($date));
		}
		
		return false;
	}
	
	function getCitationDate(){
		$months = $this->getMonths();								
								
		if($this->hasCitationDate()){
			$date  = isset($this->_data['citDay']) ? $this->_data['citDay'] : "";
			$date .= isset($this->_data['citMonth']) ? " " . $months[$this->_data['citMonth']] : "";
			$date .= " " . $this->_data['citYear'];


			
			return ltrim(rtrim($date));
		}
		
		return false;
	}
	
	function getRetractionDate(){
		$months = $this->getMonths();								
								
		if($this->hasRetractionDate()){
			$date  = $this->_data['retYear'];
			$date .= isset($this->_data['retMonth']) ? " " . $months[$this->_data['retMonth']] : "";
			$date .= isset($this->_data['retDay']) ? " " . $this->_data['retDay'] : "";
			
			return $date;
		}
		
		return ltrim(rtrim($date));
	}
	
	function getCorrectionDate(){
		$months = $this->getMonths();								
								
		if($this->hasCorrectionDate()){
			$date  = $this->_data['corYear'];
			$date .= isset($this->_data['corMonth']) ? " " . $months[$this->_data['corMonth']] : "";
			$date .= isset($this->_data['corDay']) ? " " . $this->_data['corDay'] : "";
			
			return ltrim(rtrim($date));
		}
		
		return false;
	}
	
	function getVolume(){
		return isset($this->_data['volume']) ? $this->_data['volume'] : false;
	}
	
	function getVolumeSuppl(){
		return isset($this->_data['suppl_volume']) ? $this->_data['suppl_volume'] : false;
	}
	
	function getVolumePart(){
		return isset($this->_data['part_volume']) ? $this->_data['part_volume'] : false;
	}
	
	function getIssue(){
		return isset($this->_data['issue']) ? $this->_data['issue'] : false;
	}
	
	function getIssueSuppl(){
		return isset($this->_data['suppl_issue']) ? $this->_data['suppl_issue'] : false;
	}
	
	function getIssuePart(){
		return isset($this->_data['part_issue']) ? $this->_data['part_issue'] : false;
	}
	
	function getPages(){
		if(!isset($this->_data['page_initial'])) return false;
		
		$pages = $this->_data['page_initial'];
		$pages .= isset($this->_data['page_final']) && $this->_data['page_final'] != "" ? "-" . $this->_data['page_final'] : "";
		
		return $pages;
	}
	
	function getRetractionPages(){
		if(!isset($this->_data['ret_page_initial'])) return false;
		
		$pages = $this->_data['ret_page_initial'];
		$pages .= isset($this->_data['ret_page_final']) && $this->_data['ret_page_final'] != "" ? "-" . $this->_data['ret_page_final'] : "";
		
		return $pages;
	}
	
	function getCorrectionPages(){
		if(!isset($this->_data['cor_page_initial'])) return false;
		
		$pages = $this->_data['cor_page_initial'];
		$pages .= isset($this->_data['cor_page_final']) && $this->_data['cor_page_final'] != "" ? "-" . $this->_data['cor_page_final'] : "";
		
		return $pages;
	}
	
	function getDiscussion(){
		return isset($this->_data['discussion']) ? $this->_data['discussion'] : false;
	}
	
	function isRetraction(){
		if(isset($this->_data['retAuthors'])) return true;
		
		return false;
	}
	
	function isRetractionOf(){
		if($this->isRetraction() && $this->_data['retId'] == "contains") return true;
		
		return false;
	}
	
	function isRetractionIn(){
		if($this->isRetraction() && $this->_data['retId'] == "object_of") return true;
		
		return false;
	}
	
	function getRetraction(){
		$retraction  = $this->_data['retAuthors'] . ". "; 
		$retraction .= $this->_data['retSource'] . ". ";
		$retraction .= $this->getRetractionDate() . ";";
		$retraction .= $this->_data['retVolume'];
		$retraction .= "(" . $this->_data['retIssue'] . "):";
		$retraction .= $this->getRetractionPages();
		
		return $retraction;
	}
	
	function isCorrection(){
		if(isset($this->_data['corJournal'])) return true;
		
		return false;
	}
	
	function getCorrection(){
		$correction  = $this->_data['corJournal'] . ". ";
		$correction .= $this->_data['corDate'] . ";";
		$correction .= $this->_data['corVolume'];
		$correction .= "(" . $this->_data['corIssue'] . "):";
		$correction .= $this->_data['corPageFrom'] . "-" . $this->_data['corPageTo'];
		
		return $correction;
	}
	
	function isErratum(){
		if(isset($this->_data['errJournal'])) return true;
		
		return false;
	}
	
	function getErratum(){
		$erratum  = $this->_data['errJournal'] . ". ";
		$erratum .= $this->_data['errDate'] . ";";
		$erratum .= $this->_data['errVolume'];
		$erratum .= "(" . $this->_data['errIssue'] . "):";
		$erratum .= $this->_data['errPageFrom'] . "-" . $this->_data['errPageTo'];
		
		return $correction;
	}
	
	function getMonths(){
		$months = array(1 => "Ene", 
						2 => "Feb", 
						3 => "Mar", 
						4 => "Abr", 
						5 => "May", 
						6 => "Jun", 
						7 => "Jul", 
						8 => "Ago", 
						9 => "Sep", 
						10 => "Oct", 
						11 => "Nov", 
						12 => "Dic") ;
						
		return $months;
	}
	
	function getEdition(){
		return isset($this->_data['edition']) ? $this->_data['edition'] : false;
	}
	
	function getPubPlace(){
		return isset($this->_data['pubPlace']) ? $this->_data['pubPlace'] : false;
	}
	
	function getEditorial(){
		return isset($this->_data['editorial']) ? $this->_data['editorial'] : false;
	}
	
	function getEditors(){
		return isset($this->_data['editors']) ? $this->_data['editors'] : false;
	}
	
	function getState(){
		return isset($this->_data['state']) ? $this->_data['state'] : false;
	}
	
	function isMonograph(){
		if(isset($this->_data['edition'])) return true;
		elseif(isset($this->_data['pubPlace'])) return true;
		elseif(isset($this->_data['editorial'])) return true;
		elseif(isset($this->_data['state'])) return true;
		
		return false;
	}
	
	function isArticleInJournal(){
		if(isset($this->_data['source'])) return true;
		elseif(isset($this->_data['volume'])) return true;
		elseif(isset($this->_data['issue'])) return true;
		
		return false;
	}
	
	function isElectronicMaterial(){
		if(isset($this->_data['pubDate'])) return true;
		elseif(isset($this->_data['citDate'])) return true;
		elseif(isset($this->_data['url'])) return true;
		
		return false;
	}
	
	/*function isOtherPublishedMaterial(){
		if(isset($this->_data['pubDate'])) return true;
		elseif(isset($this->_data['citDate'])) return true;
		elseif(isset($this->_data['url'])) return true;
		
		return false;
	}
	
	function isUnPublishedMaterial(){
		if(isset($this->_data['pubDate'])) return true;
		elseif(isset($this->_data['citDate'])) return true;
		elseif(isset($this->_data['url'])) return true;
		
		return false;
	}*/
	
	function getUrl(){
		return isset($this->_data['url']) ? $this->_data['url'] : false;
	}
	
	function getBookChapter(){
		$bookChapter = isset($this->_data['bookChapAuthors']) ? $this->_data['bookChapAuthors'] : "";
		$bookChapter .= isset($this->_data['bookChapTitle']) ? ". " . $this->_data['bookChapTitle'] : "";

		return $bookChapter != "" ? $bookChapter : false;
	}
	
	function getTypeTitle(){
		return isset($this->_data['typeTitle']) ? $this->_data['typeTitle'] : false;
	}
	
	function getTypeSource(){
		return isset($this->_data['typeSource']) ? $this->_data['typeSource'] : false;
	}
	
	function getPageCount(){
		return isset($this->_data['pageCount']) ? $this->_data['pageCount'] : false;
	}
	
	function getSection(){
		return isset($this->_data['section']) ? $this->_data['section'] : false;
	}
	
	function getColumn(){
		return isset($this->_data['column']) ? $this->_data['column'] : false;
	}
	
	function getForthcomingDate(){
		return isset($this->_data['forthcoming']) ? $this->_data['forthcoming'] : false;
	}
	
	function getSitePage(){
		return isset($this->_data['sitePage']) ? $this->_data['sitePage'] : false;
	}
	
	function getConferenceSponsor(){
		return isset($this->_data['confSponsor']) ? $this->_data['confSponsor'] : false;
	}
	
	function getConferenceTitle(){
		return isset($this->_data['confTitle']) ? $this->_data['confTitle'] : false;
	}
	
	function getConferenceCity(){
		return isset($this->_data['confCity']) ? $this->_data['confCity'] : false;
	}
	
	function getConferenceState(){
		return isset($this->_data['confState']) ? $this->_data['confState'] : false;
	}
	
	function getConference(){
		$conf  = $this->getConferenceSponsor() ? $this->getConferenceSponsor() . ": " : "";
		$conf .= $this->getConferenceTitle() ? "Proceedings of the " . $this->getConferenceTitle() . "; " : "";
		$conf .= $this->hasConferenceDate() ? $this->getConferenceDate() . "; " : "";
		$conf .= $this->getConferenceCity() ? $this->getConferenceCity() . ", " : "";
		$conf .= $this->getConferenceState() ? $this->getConferenceState() . ". " : "";

		return $conf != "" ? $conf : false;
	}
	
	function getConferenceArticleAuthors(){
		return isset($this->_data['confArtAuthors']) ? $this->_data['confArtAuthors'] : false;
	}
	
	function getConferenceArticleTitle(){
		return isset($this->_data['confArtTitle']) ? $this->_data['confArtTitle'] : false;
	}
	
	function getConferenceArticle(){
		$confArticle = $this->getConferenceArticleAuthors() ? $this->getConferenceArticleAuthors() . ". " : "";
		$confArticle .= $this->getConferenceArticleTitle() ? $this->getConferenceArticleTitle() : "";

		return $confArticle != "" ? $confArticle : false;
	}
}
?>