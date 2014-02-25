<?php

namespace OCA\OCMessage\Controller;

class BBCodes{

	private $bbset = array();
	private $text = "";
	private $htmlText = "";
	public function __construct($text, $bbset=array()){
		$this->bbset = array_merge($bbset, $this->bbset);
		$this->text = $text;
	}

	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	function convertToHTML(){
		$tmpText = $this->text;


		foreach ($this->bbset as $bbset){
			$bbOpen = $bbset->openingBB;
			$htmlOpen = $bbset->openingHTML;
				
			$bbOpen = preg_replace("/%[a-zA-Z0-9]%/", "(.*)", $bbOpen);
			$numArgs = preg_match("/%[a-zA-Z0-9]%/", $htmlOpen);
			$htmlOpen = preg_replace("/%[a-zA-Z0-9]%/", "\\$1", $htmlOpen);
				

			$oneTag = (($bbset->closingHTML == "") && ($bbset->closingBB == ""));

			if ($oneTag){
				$tmpText = preg_replace('#\['.$bbOpen.'\]#isU', '<'.$htmlOpen.'>', $tmpText);
			} else {
				$tmpText = preg_replace('#\['.$bbOpen.'\](.*)\['.$bbset->closingBB.'\]#isU', '<'.$htmlOpen.'>'.(($numArgs == 0)?'
						$1':'$2').'<'.$bbset->closingHTML.'>', $tmpText);
			}
		}

		return $tmpText;
	}
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	public function generateHTML(){
		$this->htmlText = $this->convertToHTML();
	}
	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	public function getHTML(){
		$this->generateHTML();
		return $this->htmlText;
	}



	/**
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 * @Ajax
	 */
	public function addBBSet($openingBB, $closingBB=NULL, $openingHTML=NULL, $closingHTML=NULL){
		if (is_a($openingBB, "BBSet")){
			$this->bbset[] = $openingBB;
		} else {
			$this->bbset[] = new BBSet($openingBB, $closingBB, $openingHTML, $closingHTML);
		}
	}
}
class BBSet{
	public $openingBB, $closingBB, $openingHTML, $closingHTML, $name;
	public function __construct($openingBB, $closingBB, $openingHTML="", $closingHTML=""){
		if ($openingHTML == "" && $closingHTML==""){
			$this->openingBB = $openingBB;
			$this->closingBB = "";
			$this->openingHTML = $closingBB;
			$this->closingHTML = "";
		} else {
			$this->openingBB = $openingBB;
			$this->closingBB = $closingBB;
			$this->openingHTML = $openingHTML;
			$this->closingHTML = $closingHTML;
		}
	}
}
