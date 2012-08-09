<?php
/**
 * 
 * @author Francesc Sans
 * @version 1.0.2
 * @copyright Network BCN Software, 17 Aug, 2009
 * 
 **/

class FieldDescriptorVO {
	
	public function __construct() {
	}
	
	public $autoenter;
	public $fourdigityear;
	public $global;
	public $maxrepeat;
	public $name;
	public $notempty;
	public $numericonly;
	public $result;
	public $timeofday;
	public $type;

	public $_explicitType = "lib.filemaker.vo.FieldDescriptorVO";
	
	public function __set_state( $assoc ) {
		
		$this->autoenter 	= (string) $assoc->autoenter;
		$this->fourdigityear= (string) $assoc->fourdigityear;
		$this->global 		= (string) $assoc->global;
		$this->maxrepeat 	= (string) $assoc->maxrepeat;
		$this->name 		= (string) $assoc->name;
		$this->notempty		= (string) $assoc->notempty;
		$this->numericonly 	= (string) $assoc->numericonly;
		$this->result 		= (string) $assoc->result;
		$this->timeofday 	= (string) $assoc->timeofday;
		$this->type 		= (string) $assoc->type;

	}
}
?>