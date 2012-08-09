<?php
/*
*Copyright (c) 2008 Network BCN Sotware
*/
class FmerrVO {
	
	public function __construct() { }
	
	public $recid;
	public $sku; 
	public $status; 
	public $type; 
	public $errnum; 
	public $description; 
	public $group; 
	public $coment; 
	public $iid;
	
	public $_explicitType =  "org.fmclub.forum.vo.FmerrVO";
	
	public function __set_state ( $assoc ) {
		$this->recid	= (int)	$assoc->recid;
		$this->sku	= (string)	$assoc->sku;
		$this->status	= (string)	$assoc->status;
		$this->type	= (string)	$assoc->type;
		$this->errnum	= (string)	$assoc->errnum;
		$this->description	= (string)	$assoc->description;
		$this->group	= (string)	$assoc->group;
		$this->coment	= (string)	$assoc->coment;
		$this->iid	= (string)	$assoc->iid;
	}
}
?>
