<?php 

/**
 * 
 * @author Francesc Sans
 * @version 1.0.1
 * @copyright Network BCN Software, 17 Aug, 2009
 * 
 **/

class Factory { 
	
	public static function create($className, $params = NULL) { 
	
		if(class_exists($className)) { 
			if($params == NULL)		
				return new $className(); 
			else { 
				$obj = new ReflectionClass($className); 
				return $obj->newInstanceArgs($params); 
			}	  
		} 
		 
		throw new Exception("Class [ $className ] not found..."); 
	} 


	public static function autoload($className, $classpath) { 
		
		if(file_exists($file = $classpath . "/" . $className . ".php")) {
			require_once $file; 
			return true;
		}else{ 
			throw new Exception("File [" . $classpath . "/" . $className . ".php ] not found...");
			return false;
		}
	}
	 
} 


?>