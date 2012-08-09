<?php

/**
 * 
 * @author Francesc Sans
 * @version 1.0.2
 * @copyright Network BCN Software, 17 Aug, 2009
 * 
 **/

	interface IFilemakerDao {
	
		public function __construct	();
		public function __destruct	();
	
		public function find			($param, $filter, $skip	);
		public function findCompound	($param, $filter, $skip	);
		public function createOne		($param, $filter		);
		public function updateOne		($param, $filter		);
		public function deleteOne		($param					);
		public function duplicateOne	($param					);
		public function view			($param					);
		
		//protected function doQuery		($postargs, $context	);

	}

?>