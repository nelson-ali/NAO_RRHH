<?php

return new \Phalcon\Config(array(
	'database' => array(
		'adapter'     => 'Twm\Db\Adapter\Pdo\Mssql',		
		'host'		=> '192.168.10.40',
		'username'	=> 'sa',
		'password'	=> 'Sistemas2015',
		'dbname'	=> 'prueba',
		'pdoType'       => 'dblib',
		'dialectClass'	=> 'Twm\Db\Dialect\Mssql'	
	),
	'application' => array(
		'controllersDir' => __DIR__ . '/../../app/controllers/',
		'modelsDir'      => __DIR__ . '/../../app/models/',
		'viewsDir'       => __DIR__ . '/../../app/views/',
		'pluginsDir'     => __DIR__ . '/../../app/plugins/',
		'libraryDir'     => __DIR__ . '/../../app/library/',
		'cacheDir'       => __DIR__ . '/../../app/cache/',
		'baseUri'        => '/test/',
	)
));
