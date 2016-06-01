<?php
// Sets the verbosity
define('VER__GOOD'   ,0);
define('VER__INFO'   ,1); // This verbosity makes the forcolor of output WHITE
define('VER__WARNING',2); // This verbosity makes the forcolor of output YELLOW
define('VER__ERROR'  ,3); // This verbosity makes the forcolor of output PURPLE
define('VER__FATAL'  ,4); // This verbosity makes the forcolor of output RED

//@todo find out what are these
define('_DEFINE_PARAM',1000);
define('_FILE_NOT_EXIST',1001);

class config{
	public static function getAppConfig(){
		$app_config = array(
						'system-messages'=>array(
												'source-database'=>array('error'=>'>>> Source Database is missing !!!',
																         'warning'=>'>>> Please define the a Source Database !!!'),
												'source-host'=>    array('error'=>'>>> Source Host is missing !!!',
																         'warning'=>'>>> Please define the a Source Host !!!'),
												'source-port'=>    array('error'=>'>>> Source Port is missing !!!',
																         'warning'=>'>>> Please define the a Source Port !!!'),
												'source-username'=>array('error'=>'>>> Source Username is missing !!!',
																         'warning'=>'>>> Please define the a Source Username !!!'),
												'source-password'=>array('error'=>'>>> Source Password is missing !!!',
																         'warning'=>'>>> Please define the a Source Password !!!'),
												'target-database'=>array('error'=>'>>> Target Database is missing !!!',
																         'warning'=>'>>> Please define the a Target Database !!!'),
												'target-host'=>    array('error'=>'>>> Target Host is missing !!!',
																         'warning'=>'>>> Please define the a Target Host !!!'),
												'target-port'=>    array('error'=>'>>> Target Port is missing !!!',
																         'warning'=>'>>> Please define the a Target Port !!!'),
												'target-username'=>array('error'=>'>>> Target Username is missing !!!',
																         'warning'=>'>>> Please define the a Target Username !!!'),
												'target-password'=>array('error'=>'>>> Target Password is missing !!!',
																         'warning'=>'>>> Please define the a Target Password !!!'),
												'error-level'    =>array('error'=>'>>> Please define the report level (0,1,2,3,4) !!!',
																         'warning'=>'>>> Reporting level is missing (0,1,2,3,4) !!!'),
												'test'   		 =>array('error'=>'>>> Please define the test elements (missing-tables,missing-fields,...) For help use --help !!!',
																         'warning'=>'>>> Test elements are missing !!!')
		)
				);
		return $app_config;		
	}							
}