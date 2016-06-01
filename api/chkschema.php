<?php
error_reporting(E_ALL);

// Sets the verbosity 
define('VER__GOOD'   ,0);
define('VER__INFO'   ,1); // This verbosity makes the forcolor of output WHITE
define('VER__WARNING',2); // This verbosity makes the forcolor of output YELLOW
define('VER__ERROR'  ,3); // This verbosity makes the forcolor of output PURPLE
define('VER__FATAL'  ,4); // This verbosity makes the forcolor of output RED

//@todo find out what are these
define('_DEFINE_PARAM',1000);   
define('_FILE_NOT_EXIST',1001);

// Load the Bootstrap
require_once '../app/bootstrap.php';

//@todo find out what is this rules_object. i has been changed dont forget
//Load the Rule Configurations into Objects
$rules_object = array();
foreach(functions::getRulesConfig() as $one_rule){
    rules::$global_rules[] = new rules($one_rule);
}

$command = new command($argv);

$info = array('source_host'    => $command->getSourceHost(),
			  'source_db'      => $command->getSourceDB(),
			  'source_username'=> $command->getSourceUsrName(),
			  'source_password'=> $command->getSourcePWD(),
			  'source_port'    => $command->getSourcePort(),
			  'target_host'    => $command->getTargetHost(),
			  'target_db'      => $command->getTargetDB(),
			  'target_username'=> $command->getTargetUsrName(),
			  'target_password'=> $command->getTargetPWD(),
			  'target_port'    => $command->getTargetPort(),

			  'color'          => $command->getColor(),
			  'report_level'   => $command->getReportLevel(),  // anything but 0 means it is an error 
			  'sql_fix'        => $command->getSQLFix()
			);

// Load the Help manual on the console
if ($command->getHelp()) { echo(file_get_contents('../App/Help.txt')); functions::calMaxErrorLevel(_FILE_NOT_EXIST, TRUE, $command->getFailLevel());}

// to adjust the header output
$max_length = functions::getMaxLength(array("Host: {$command->getSourceHost()}",
								  	  		    "Port: {$command->getSourcePort()}",
									  		    "Database: {$command->getSourceDB()}",
									  		    "Username: {$command->getSourceUsrName()}",
									  		    "Password: {$command->getSourcePWD()}"
										    ));

$header =PHP_EOL."-----------------------------------------------------------------------------------------------------------------".PHP_EOL. 
functions::formatOutput("Host: {$command->getSourceHost()}", $max_length['length']+5)."Host: {$command->getTargetHost()}".PHP_EOL.
functions::formatOutput("Port: {$command->getSourcePort()}", $max_length['length']+5)."Port: {$command->getTargetPort()}".PHP_EOL.
functions::formatOutput("Database: {$command->getSourceDB()}", $max_length['length']+5)."Database: {$command->getTargetDB()}".PHP_EOL.
functions::formatOutput("Username: {$command->getSourceUsrName()}", $max_length['length']+5)."Username: {$command->getTargetUsrName()}".PHP_EOL.
functions::formatOutput("Password: {$command->getSourcePWD()}", $max_length['length']+5)."Password: {$command->getTargetPWD()}".PHP_EOL.
functions::formatOutput("  ______", $max_length['length'] + 5)." ______".PHP_EOL.
functions::formatOutput(" /      \ ", $max_length['length'] + 5)."/      \ ".PHP_EOL.
functions::formatOutput("|\_____ /|", $max_length['length'] + 4)."|\______/|".PHP_EOL.
functions::formatOutput("|        | <", $max_length['length']+1 ,"-").">  |        |".PHP_EOL.
functions::formatOutput("| Source |", $max_length['length'] + 4)."| Target |".PHP_EOL.
functions::formatOutput(" \______/", $max_length['length'] + 5)."\______/".PHP_EOL.
"-----------------------------------------------------------------------------------------------------------------".PHP_EOL.PHP_EOL;
echo $header;
// Making sure all parameters have been defined
if(	$command->getSourcePort()=='' || $command->getSourceDB()=='' || $command->getSourceUsrName()=='' || $command->getSourcePWD()=='' || 
	$command->getTargetPort()=='' || $command->getTargetDB()=='' || $command->getTargetUsrName()=='' || $command->getTargetPWD()==''){
		echo ('Please define all necessarily parameters...'.PHP_EOL.PHP_EOL);
		functions::calMaxErrorLevel(_DEFINE_PARAM, TRUE, $command->getFailLevel());
	}

// If specific test been defined in the command line here it will get loaded	
$test = $command->getTests();

// if tests were not defined in the command line then read the tests from RuleConfig file
if ($test['test-flag']===FALSE){	
    $test = App_Config_RulesConfig::getTestConfig($command->getTargetDB()); 
}	

// Start running the schema checker
$chk = new App_Exphandler_General($info, $test);
$result = $chk->schemaChecker();

// If verbose requested print the report
if($command->getVerbose()){	echo $result; }

// exist status
functions::exitMe($command->getFailLevel());