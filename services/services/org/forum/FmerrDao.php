<?php
	
/**
 * 
 * @author Francesc Sans
 * @version $Id$
 * @copyright Network BCN Software, 23 July, 2009
 * 
 **/

	include_once (DAO_LIB . "filemaker/FilemakerDao.php");

	class FmerrDao extends FilemakerDao {
	
		public $context	 		= "xml_fmerr";
		public $valueObject 	= "FmerrVO";

	}

?>