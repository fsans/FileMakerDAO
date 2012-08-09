<?php

class MetadataVO {
	
	public function __construct() {
	}
	
	public $error;
	public $version;
	public $dateformat;
	public $timeformat;
	public $timestampformat;
	public $totalcount;
	public $count;
	public $fetch;

	public $_explicitType = "lib.filemaker.vo.MetadataVO";
	
	public function __set_state( $assoc ) {
		$this->error 			= (int)$assoc->error;
		$this->version			= (string)$assoc->version;
		$this->dateformat 		= (string)$assoc->dateformat;
		$this->timeformat 		= (string)$assoc->timeformat;
		$this->timestampformat 	= (string)$assoc->timestampformat;
		$this->totalcount		= (int)$assoc->totalcount;
		$this->count 			= (int)$assoc->count;
		$this->fetch 			= (int)$assoc->fetch;


	}
}
?>