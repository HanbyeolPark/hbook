<?php
class AladdinXMLParser {
	var $inItems = FALSE;
	var $currentElement = '';
	var $itemInfo = array();
	var $itemList = array();

	function startHandler($parser, $element, $attr){
		if($element=="ITEM") { $this->inItems = TRUE; }
		$this->currentElement = $element;
	}

	function endHandler($parser, $element){ 
		if($element=="ITEM") { 
			$this->itemList[] = $this->itemInfo; 
			$this->itemInfo = array(); 
			$this->inItems = FALSE; 
		}
		$this->currentElement = ''; 
	}
	function cdataHandler($parser, $cdata){ 
		if($this->inItems==TRUE){ 
			if($this->currentElement=="TITLE"){ 
				$this->itemInfo["TITLE"] = $this->itemInfo["TITLE"].$cdata;
			} else if($this->currentElement=="LINK"){ 
				$this->itemInfo["LINK"] = $this->itemInfo["LINK"].$cdata; 
			} else if($this->currentElement=="COVER") {
				$this->itemInfo["COVER"] = $this->itemInfo["COVER"].$cdata;
			} else if($this->currentElement=="AUTHOR") {
				$this->itemInfo["AUTHOR"] = $this->itemInfo["AUTHOR"].$cdata;
			} else if($this->currentElement=="PUBLISHER") {
				$this->itemInfo["PUBLISHER"] = $this->itemInfo["PUBLISHER"].$cdata;
			}
		} 
	} 
} 
?> 