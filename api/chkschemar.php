<?php
//@todo find out what the hell is this
error_reporting(E_ALL);

// Load the Bootstrap
require_once '../app/bootstrap.php';

//@todo find out what is this rules_object. i has been changed dont forget
//Load the Rule Configurations into Objects
$rules_object = array();
foreach(functions::getExceptionRules() as $one_rule){
    buildRules::$global_rules[] = new buildRules($one_rule);
}

// Digest the command line parameters and sets the proper variables for further use
$command = new commandLine($argv);
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

			  'color'          => $command->getColor(),      // If TRUE it means the output on the screen will be in color
			  'report_level'   => $command->getReportLevel(),// anything but 0 means it is an error 
			  'sql_fix'        => $command->getSQLFix()      // If TRUE then the SQL suggestions to fix the issue will be provided 
			);

// Load the help manual on the console
if ($command->getHelp()) { 
    echo(file_get_contents('../app/help.txt')); 
    functions::calMaxErrorLevel(_FILE_NOT_EXIST, TRUE, $command->getFailLevel());
}
// output the header on the screeen if verbosity was set
if ($command->getVerbose()==TRUE) {
    echo (functions::parseOutoutHeader($info));
}

// Making sure all parameters have been defined
if(	$command->getSourcePort()=='' || $command->getSourceDB()=='' || $command->getSourceUsrName()=='' || $command->getSourcePWD()=='' || 
	$command->getTargetPort()=='' || $command->getTargetDB()=='' || $command->getTargetUsrName()=='' || $command->getTargetPWD()==''){
		echo ('Please define all necessarily parameters or use the script with --help to see the details...'.PHP_EOL.PHP_EOL);
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