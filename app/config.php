<?php
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