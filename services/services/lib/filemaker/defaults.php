<?php

/**
 * 
 * @author Francesc Sans
 * @version 2.0.2 (30.MAY.2012)
 * @copyright Network BCN Software
 * 
 **/

$config = parse_ini_file("config.ini", 1 );

define('ROOT'			, SERVICES_BASE );
define('PACKAGE'		, $config['package'] );
define('HOST'			, $config['host'] );
define('PROTOCOL'		, $config['protocol'] );
define('PORT'			, $config['port'] );
//define('FMIURI'			, $config['fmiUri'] );
//define('FMILAYOUTURI'	, $config['fmiLayoutUri'] );
define('FMIURI'			, "/fmi/xml/fmresultset.xml" );
define('FMILAYOUTURI'	, "/fmi/xml/FMPXMLLAYOUT.xml" );

define('DB_NAME'		, $config['dbName'] );
define('DB_USER'		, $config['dbUser'] );
define('DB_PASS'		, $config['dbPass'] );

define('DB_SOURCE'		, PROTOCOL.HOST.PORT.FMIURI );
define('LAYOUT_INFO'	, PROTOCOL.HOST.PORT.FMILAYOUTURI );

//define('EXCLUSIONS'		, $config['exclusions'] );
define('EXCLUSIONS'		, "'sku','recid','_explicitType','dateIn','dateMod','isParent','hasChilds','dataEntr','dataEntrada','serie','idx','fase_forfait'");
	
define('LOG'			, $config['log']	== 1 ? true : false );
define('DEBUG'			, $config['debug']	== 1 ? true : false );
//define('LOG_FILE'		, ROOT . 'log/' . PACKAGE . '.log' );
define('LOG_FILE'		, '/var/log/amf/' . PACKAGE . '.log' );

define('EMAIL_LOGIN_ATTEMPTS' , $config['emailLogins'] 	== 1 ? true : false );
define('ADMIN'			, $config['admin'] );
define('ADMIN_EMAIL'	, $config['adminEmail'] );

// added on v2.0.1

define('ENTITY_PREFIX'	, 'id_' );
define('RESOLVE_ENTITIES'	, true );

// added on v2.0.2

define('FILEMAKER_SERVER_USER'	, $config['fmserveruser']  );
define('FILEMAKER_SERVER_PASS'	, $config['fmserverpassword']  );

define('FILEMANAGER_USER'	, $config['filemanageruser']  );
define('FILEMANAGER_PASS'	, $config['filemanagerpassword']  );

?>