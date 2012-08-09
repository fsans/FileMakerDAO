<?php

/**
 * 
 * @author Francesc Sans
 * @version 1.0.1
 * @copyright Network BCN Software, 17 Aug, 2009
 * 
 **/

include_once ("IFilemakerDaoExt.php");
require_once ("StdFunc.php");


class FilemakerDaoExt implements IFilemakerDaoExt{
	
	public $context ;
	public $findcontext ;
	public $indexcontext ;
	public $valueObject;
	
	public function __construct() {}

	public function __destruct() {}
	
	public function findlist($param, $filter, $skip){
		$postargs 	 = "-find";
		//$postargs 	.= "&keywords=" . $param["keywords"];
		$postargs 	.= StdFunc::parsePostargs($param, $filter);

		if($skip!=null){
			$postargs .= "&-skip=" 	. $skip[0];
			$postargs .= "&-max=" 	. $skip[1];
		}
		
		return( $this->doQuery($postargs, $this->indexcontext) );
	}
	
	public function find($param, $filter, $skip){
		$context 	 = null;
		$postargs 	 = "-find";
		$postargs 	.= StdFunc::parsePostargs($param, $filter);
		
		if($skip!=null){
			$postargs .= "&-skip=" 	. $skip[0];
			$postargs .= "&-max=" 	. $skip[1];
		}
		
		// if using "keywords" then switch context to keywords layout
		if() {
			$context = $this->keycontext
		}
		
		return( $this->doQuery($postargs, $context) );
	}
	
	public function createOne($param, $filter){
		$postargs 	 = "-new";
		$postargs 	.= StdFunc::parsePostargs($param, $filter);
		return( $this->doQuery($postargs, null) );
	}
	
	public function updateOne($param, $filter){
		$postargs 	 = "-edit";
		$postargs 	.= StdFunc::parsePostargs($param, $filter);
		$postargs 	.= "&-recid=" . $param['recid'];
		return( $this->doQuery($postargs, null) );
	}

	public function deleteOne($param){
		$postargs 	 = "-delete";
		$postargs 	.= "&-recid=" . $param['recid'];
		return( $this->doQuery($postargs, null) );
	}
	
	
	public function duplicateOne($param){
		$postargs 	 = "-dup";
		$postargs 	.= "&-recid=" . $param['recid'];
		return( $this->doQuery($postargs, null) );
	}
	
	public function view($param){
		$postargs 	 = "-view";
		$postargs 	.= "&-recid=" . $param['recid'];;
		return( $this->doQuery($postargs, null) );
	}
	
	protected function doQuery($postargs, $context){
		$resultset 			= array();
		$metadata 			= array();
		//$fieldDescriptor 	= array();
		//$layoutDescriptor = array();
		
		$valueObject 	= '';
		$resultObject 	= array();
		
		$postargs 	.= "&-db=" 	. DB_NAME;
		
		if($context == null){
			$postargs 	.= "&-lay=" . $this->context;
		}else{
			$postargs 	.= "&-lay=" . $context;
		}
		
		$postargs 	.= "&-lay.response=" . $this->context;
		
		StdFunc::logString($postargs, get_class($this));
		
		$xmlstr 			= StdFunc::queryDataSource($postargs);
		$resultset 			= StdFunc::getRecordset($xmlstr, $this->valueObject);
		$metadata 			= StdFunc::getMetadata($xmlstr);
		//$fieldDescriptor 	= StdFunc::getFieldDescriptors($xmlstr);
		//$layoutDescriptor	= StdFunc::getLayoutDescriptors($xmlstr);
		
		array_push($resultObject, $resultset);
		array_push($resultObject, $metadata);
		//array_push($resultObject, $fieldDescriptor);
		//array_push($resultObject, $layoutDescriptor);
		
		return( $resultObject );
	}

}

?>