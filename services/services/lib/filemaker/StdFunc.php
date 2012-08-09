<?php

/**
 * 
 * @author Francesc Sans
 * @version 1.1.0 mod 06.JUN.2011 fsans@ntwk.es
 * @copyright Network BCN Software, 17 Aug, 2009
 * 
 **/

include_once "defaults.php";
require_once('SimpleXMLElementExtended.php');
require_once('Factory.php');
require_once('FilemakerProxy.php');
require_once('vo/MetadataVO.php');
require_once('vo/VlistVO.php');
require_once('vo/VlistItemVO.php');
require_once('vo/FieldDescriptorVO.php');


class StdFunc {

	public function __construct() {}

	public function __destruct() {}
	
	
	static function logString() {
		$size = 0;
		$duration = 0;
		if(LOG){
			$argc = func_num_args();
			if( $argc > 0 ) {
				$str = func_get_arg(0);
				
			}
			if( $argc > 1 ) {
				$func = func_get_arg(1);
			}
			if( $argc > 2 ) {
				$size = func_get_arg(2);
			}
			if( $argc > 3 ) {
				$duration = func_get_arg(3);
			}
			$fh = fopen( LOG_FILE, "a" );
			//fwrite( $fh, (date("c"))."; ".$_SERVER['REMOTE_ADDR']."; ".DB_NAME."; ".$func."; ".$size."; ".$duration."; ".$str."\n" );
			fwrite( $fh, (date("c"))."; ".$_SERVER['REMOTE_ADDR']."; ".DB_NAME."; ".$func."; ".$str."\n" );
			fclose( $fh );
		}
	}
		
	static function logDebug($data) {
		if(LOG && DEBUG){
			$fh = fopen( LOG_FILE, "a" );
			fwrite( $fh, (date("c"))."; ".$_SERVER['REMOTE_ADDR']."; ".DB_NAME."; ".$data."\n" );
			fclose( $fh );
		}
	}
	
	public function parseFilteredPostargs($param, $fields) {
		$postargs = "";
		if($param != null){
			foreach ($fields as $field) {
				$value = $param[$field];
				$postargs .= "&". $field ."=" .urlencode($value);
				StdFunc::logDebug($field . "=" . $value);
			}
		}
		return ($postargs);
	}	
	
	public function parseRawPostargs($param) {
		$exc = array('sku','recid','_explicitType','dateIn','dateMod', 'isParent','hasChilds','dataEntr','dataEntrada','serie','idx','fase_forfait');
		$postargs = "";
		if($param != null){
			foreach ($param as $key => $value ) {
				$tempstr = $key ."=" .$value;
				if( ($value != '' || $value == 0)  && array_search($key, $exc) === false ){
					$postargs .= "&". $key ."=" .urlencode($value);
					$tempstr .= " \tsent -> " .$value;
				}
			self::logDebug($tempstr);
			}
		}
		return ($postargs);
	}
	
	public function parsePostargs($param) {
		if( func_num_args() > 1 && func_get_arg(1) != null) {
			$fields = func_get_arg(1);
			StdFunc::logDebug("FILTERED PARSE ");
			return StdFunc::parseFilteredPostargs($param, $fields);
		}else{
			StdFunc::logDebug("RAW PARSE ");
			return StdFunc::parseRawPostargs($param);
		}
	}
	
	public function queryDataSource($postargs) {
		$query = curl_init();
		curl_setopt($query, CURLOPT_URL, DB_SOURCE);
		curl_setopt($query, CURLOPT_POST, 1);
		curl_setopt($query, CURLOPT_POSTFIELDS, $postargs);
		ob_start();
		curl_exec($query);
		curl_close($query);
		$xmlstr = trim(ob_get_contents());
		ob_end_clean();
		return ($xmlstr);	
	}
	
	public function getRecordset($xmlstr, $VO) { 
		$data = array();
		$xml = new SimpleXMLElementExtended($xmlstr);
		$res = $xml->resultset->record;
		foreach ($res as $record) {	
			$obj = (object) null;
			$obj->recid = (int) $record['record-id'];
			$ff = $record->field; 
			foreach ($ff as $key => $value) {
				$obj->$value['name'] = (string) $value->data;
			}
			Factory::autoload( $VO, 'vo');
			$valueObject = Factory::create( $VO );
			$valueObject->__set_state( $obj );
			array_push( $data, $valueObject );
		}
		return $data; 
	}
	
	public function queryLayout($postargs) {
		$query = curl_init();
		curl_setopt($query, CURLOPT_URL, LAYOUT_INFO);
		curl_setopt($query, CURLOPT_POST, 1);
		curl_setopt($query, CURLOPT_POSTFIELDS, $postargs);
		ob_start();
		curl_exec($query);
		curl_close($query);
		$xmlstr = trim(ob_get_contents());
		ob_end_clean();
		return ($xmlstr);	
	}
	
	public function getValuelistObjects($xmlstr) { 
		$data = array();
		$xml = new SimpleXMLElementExtended($xmlstr);
		
		$res = $xml->VALUELISTS->VALUELIST;
		
		foreach ($res as $record) {	
			
			$obj = (object) null;
			$obj->name = (string) $record->getAttribute('NAME');
			$obj->values = array();

			$value = $record->VALUE;
			
			foreach ($value as $vlistitem) {
				$objItem = (object) null;
				$objItem->name = (string) $vlistitem->getAttribute('DISPLAY');
				$objItem->value = (string) $vlistitem;
				$objItem->id = $objItem->value; // TEMPORARY REUSE VALUE AS ID

				$vlistitemVO = new VlistItemVO($objItem->name, $objItem->value, $objItem->id);
				array_push( $obj->values, $vlistitemVO );
			}

			//$vlistVO = new VlistVO($obj->name, $obj->values);
			//$vlistVO->__set_state( $obj );
			array_push( $data, $obj );
		}
		return $data; 
	}
	
	
	private function factory($class){
		return new $class;
	}
	
	
	public function getMetaData($xmlstr) { 

		$xml = new SimpleXMLElementExtended($xmlstr);
		$obj = (object) null;
		$obj->error 			= (int) 	$xml->error[0]->getAttribute('code');
		$obj->version 			= (string)	$xml->product[0]->getAttribute('version');
		$obj->dateformat 		= (string)	$xml->datasource[0]->getAttribute('date-format');
		$obj->timeformat 		= (string)	$xml->datasource[0]->getAttribute('time-format');
		$obj->timestampformat	= (string)	$xml->datasource[0]->getAttribute('timestamp-format');
		$obj->totalcount 		= (int)		$xml->datasource[0]->getAttribute('total-count');
		$obj->count 			= (int)		$xml->resultset[0]->getAttribute('count');
		$obj->fetch 			= (int)		$xml->resultset[0]->getAttribute('fetch-size');

		$metadata = new MetadataVO();
		$metadata->__set_state( $obj );
		return $metadata;
	}

	public function getFieldDescriptors($xmlstr) { 
		$xml = new SimpleXMLElementExtended($xmlstr);
		
		$obj = (object) null;
		$obj->autoenter 		= (string)	$xml->metadata[0]->getAttribute('auto-enter');
		$obj->fourdigityear 	= (string)	$xml->metadata[0]->getAttribute('four-digit-year');
		$obj->global			= (string)	$xml->metadata[0]->getAttribute('global');
		$obj->maxrepeat 		= (string)	$xml->metadata[0]->getAttribute('max-repeat');
		$obj->name 				= (string)	$xml->metadata[0]->getAttribute('name');
		$obj->notempty 			= (string)	$xml->metadata[0]->getAttribute('not-empty');
		$obj->numericonly 		= (string)	$xml->metadata[0]->getAttribute('numeric-only');
		$obj->result			= (string)	$xml->metadata[0]->getAttribute('result');
		$obj->timeofday 		= (string)	$xml->metadata[0]->getAttribute('time-of-day');
		$obj->type 				= (string)	$xml->metadata[0]->getAttribute('type');
		
		$metadata = new FieldDescriptorVO();
		$metadata->__set_state( $obj );
		return $metadata;
	}
		
	public function parseMetadata($xmlstr) { 

	}
	
	private function randSku($n) { 
	    if (!isset($n)) $n = 16;
	    $chars = array('A','B','C','D','E','F','G',
				'H','I','J','K','L','M','N','O','P',
				'R','S','T','U','W','0',
				'1','2','3','4','5','6','7','8','9'); 
    	$dim = count($chars); 
    	for ($i = 0; $i < $n; $i++) { 
    		$rand = rand(0, $dim-1); 
    		$sku = $sku.$chars[$rand]; 
    	} 
		return ($sku);
	}
	
}
?>
