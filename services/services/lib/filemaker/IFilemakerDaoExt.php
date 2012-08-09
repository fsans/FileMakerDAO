<?php

/**
 * 
 * @author Francesc Sans
 * @version 1.0.3
 * @copyright Network BCN Software, 03 Apr, 2010
 * 
 **/

	interface IFilemakerDaoExt {
	
		public function __construct	();
		public function __destruct	();
		
		public function findlist		($param, $filter, $skip	);
		public function find			($param, $filter, $skip	);
		public function createOne		($param, $filter		);
		public function updateOne		($param, $filter		);
		public function deleteOne		($param					);
		public function duplicateOne	($param					);
		public function view			($param					);
		
		//protected function doQuery		($postargs, $context	);

	}

?>