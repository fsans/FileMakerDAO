<?php

/**
 * 
 * @author Francesc Sans
 * @version 1.1.0 mod 06.JUN.2011 fsans@ntwk.es
 * @copyright Network BCN Software, 17 Aug, 2009
 * 
 **/

include_once ("IFilemakerDao.php");
require_once ("StdFunc.php");


class FilemakerDao implements IFilemakerDao{
	
	public $context ;
	public $valueObject;
	
	public function __construct() {}

	public function __destruct() {}
	
	/**
	 * Find records {vo,[fields],[skip,max]} -> Returns [[recordset], [metadata]]
	**/
	
	public function find($param, $filter, $skip)
	{
		
		// FM12 hack (empty -find now returns ERR 500)
		if($param){
			$postargs 	 = "-find";
		}else{
			$postargs 	 = "-findall";
		}
		
		$postargs 	.= StdFunc::parsePostargs($param, $filter);
		
		if($skip!=null){
			$postargs .= "&-skip=" 	. $skip[0];
			$postargs .= "&-max=" 	. $skip[1];
		}
		
		return( $this->doQuery($postargs, null) );
	}
	
	/**
	 * Find Compound request {(q1)&-q1=tags&-q1.value=transports,null,[skip,max] } -> Returns [[resultset], [metadata]]
	**/
	
	public function findCompound($compound, $filter, $skip)
	{
		
		// * filter is ignored
		// * can use pagination array parameters [skip,max]
		// * direct complex compound query
		
		// add the -query comand
		$postargs = "-query=" . $compound;
		//$postargs .= StdFunc::parsePostargs(null, null);
		
		// add pagination
		if($skip!=null){
			$postargs	.= "&-skip=" 	. $skip[0];
			$postargs	.= "&-max=" 	. $skip[1];
		}
		
		$postargs .= "&-findquery";
		
		return( $this->doQuery($postargs, null) );
	}
	
	/**
	 * Create one record {vo,[fields],[skip,max]} -> Returns [[created_item], [metadata]]
	**/
	
	public function createOne($param, $filter)
	{
		$postargs 	 = "-new";
		$postargs 	.= StdFunc::parsePostargs($param, $filter);
		return( $this->doQuery($postargs, null) );
	}
	
	/**
	 * Update one record {vo,[fields]} -> Returns [[updated_item], [metadata]]
	**/
	
	public function updateOne($param, $filter)
	{
		$postargs 	 = "-edit";
		$postargs 	.= StdFunc::parsePostargs($param, $filter);
		$postargs 	.= "&-recid=" . $param['recid'];
		return( $this->doQuery($postargs, null) );
	}

	/**
	 * Delete one record {vo} -> Returns [[metadata]]
	**/
	
	public function deleteOne($param)
	{
		$postargs 	 = "-delete";
		$postargs 	.= "&-recid=" . $param['recid'];
		return( $this->doQuery($postargs, null) );
	}
	
	/**
	 * Duplicate one record {vo} -> Returns [[recordset],[metadata]]
	**/
	
	public function duplicateOne($param)
	{
		$postargs 	 = "-dup";
		$postargs 	.= "&-recid=" . $param['recid'];
		return( $this->doQuery($postargs, null) );
	}
	
	/**
	 * Get one record {vo} -> Returns [[recordset],[metadata]]
	**/
	
	public function view($param)
	{
		$postargs 	 = "-view";
		$postargs 	.= "&-recid=" . $param['recid'];
		return( $this->doQuery($postargs, null) );
	}
	
	/**
	 * resolve entity object from entity id == {"id":"22EEDA17-C938-F466-04EC-4690DF635438"}  ->  Returns [[recordset],[metadata]]
	**/
	
	public function resolve($param)
	{
		$postargs 	 = "-find";
		// resolve only first element. id is UUID
		$postargs 	 = "&-max=1";
		$postargs 	.= "&id=" . $param['id'];
		return( $this->doQuery($postargs, null) );
		
		
	}
	
	
	public function getValuelist($param)
	{
		
		// param not used, get all valuelist in context
		return( $this->getInfo("-view", null) );
	}
	
	protected function doQuery($postargs, $context)
	{
		$resultset 			= array();
		$metadata 			= array();

		$valueObject 	= '';
		$resultObject 	= array();
		
		$postargs 	.= "&-db=" 	. DB_NAME;
		$postargs 	.= "&-lay=" . ($context == null ? $this->context : $context);

		$xmlstr 			= StdFunc::queryDataSource($postargs);
		$resultset 			= StdFunc::getRecordset($xmlstr, $this->valueObject);
		$metadata 			= StdFunc::getMetadata($xmlstr);

		array_push($resultObject, $resultset);
		array_push($resultObject, $metadata);
		
		
		//$size = strlen(serialize($resultset));
		//$duration = 0;
		
		StdFunc::logString($postargs, get_class($this));
		
		return( $resultObject );
	}
	
	protected function getInfo($postargs, $context)
	{
		$resultset 			= array();
		$metadata 			= array();
		
		$resultObject 	= array();
		
		$postargs 	.= "&-db=" 	. DB_NAME;
		$postargs 	.= "&-lay=" . ($context == null ? $this->context : $context);
		
		$xmlstr 			= StdFunc::queryLayout($postargs);
		$resultset 			= StdFunc::getValuelistObjects($xmlstr);
		
		array_push($resultObject, $resultset);
		
		StdFunc::logString($postargs, get_class($this));
		
		return( $resultObject );
	}
	

}

?>