<?php
class BBSet{
	public $openingBB, $closingBB, $openingHTML, $closingHTML, $name;
	public function __construct(String $openingBB, String $closingBB, String $openingHTML, String $closingHTML){
		$this->openingBB = $openingBB;
		$this->closingBB = $closingBB;
		$this->openingHTML = $openingHTML;
		$this->closingHTML = $closingHTML;
	}
}
class BBCodes{
	
	private $bbset = array();
	private $text = "";
	public function __construct($text, $bbset=array()){
		$this->bbset = array_merge($bbset, $this->bbset);
		$this->text = $text;
	}
	
	
	public function convertToHTML(){
		$tmpText = $this->text;
		
		
		foreach ($this->bbset as $bbset){
			$bbOpen = $bbset->openingBB;
			$htmlOpen = $bbset->openingHTML;
			
			$bbOpen = preg_replace("%[a-zA-Z0-9]%", "(.*)", $bbOpen);
			$htmlOpenOpen = preg_replace("%[a-zA-Z0-9]%", "\$1", $htmlOpen);
			$tmpText = preg_replace('#\['.$bbOpen.'\](.*)\['.$bbset->closingBB.'\]#isU', '<'.$htmlOpen.'>$1</'.$bbset->clsingHTML.'>', $tmpText);
		}
		
		return $tmpText;
	}
	
	public function addBBSet(BBSet $set){
		$this->bbset[] = $set;
	}
	
	public function addBBSet(String $openingBB, String $closingBB, String $openingHTML, String $closingHTML){
		$this->bbset[] = new BBSet($openingBB, $closingBB, $openingHTML, $closingHTML);
	}
}