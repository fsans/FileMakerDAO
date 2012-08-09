<?php

/**
 * 
 * @author Francesc Sans
 * @version 1.0.1
 * @copyright Network BCN Software, 17 Aug, 2009
 * 
 **/

class SimpleXMLElementExtended extends SimpleXMLElement{
   
	public function getAttribute($name){
		foreach($this->attributes() as $key=>$val){
			if($key == $name){
				return (string)$val;
			}
		}
	}
   
	public function getAttributeNames(){
		$cnt = 0;
		$arrTemp = array();
		foreach($this->attributes() as $a => $b) {
			$arrTemp[$cnt] = (string)$a;
			$cnt++;
		}
		return (array)$arrTemp;
	}
   
	public function getChildrenCount(){
		$cnt = 0;
		foreach($this->children() as $node){
			$cnt++;
		}
		return (int)$cnt;
	}
   
	public function getAttributeCount(){
		$cnt = 0;
		foreach($this->attributes() as $key=>$val){
			$cnt++;
		}
		return (int)$cnt;
	}
   
	public function getAttributesArray($names){
		$len = count($names);
		$arrTemp = array();
		for($i = 0; $i < $len; $i++){
			$arrTemp[$names[$i]] = $this->getAttribute((string)$names[$i]);
		}
		return (array)$arrTemp;
	}
   
}
?>
