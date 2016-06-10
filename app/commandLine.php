<?php
class commandLine{
    private $command = array();

    private $_COLOR   = FALSE; // Result will be in color if verbose is set
    private $_HELP    = FALSE; // Prints the help
    private $_VERBOSE = FALSE; // Prints the result on the screen
    private $_SQLFIX  = FALSE; // Prints the SQL fixes to correct the issue

    private $_SOURCE_HOST     = '127.0.0.1'; // The default host IP address of the Source host. Can be changed if specified.
    private $_SOURCE_PORT     = 3306;        // The Port of the Source host's database server. Can be changed if specified.
    private $_SOURCE_USER     = '';          // [*] The username of the Source database server.
    private $_SOURCE_PASSWORD = '';          // [*] The password of the Source database server.
    private $_SOURCE_DB       = '';          // [*] The database name of the Source. 

    private $_TARGET_HOST 	  = '';          // The host IP address of the Target host. Default = _SOURCE_HOST if not specified
    private $_TARGET_PORT	  = 0;           // The Port of the Target host's database server. Default = _SOURCE_PORT if not specified
    private $_TARGET_USER 	  = '';          // The username of Target database server. Default = _SOURCE_USER if not specified
    private $_TARGET_PASSWORD = '';          // The password of Target database server. Default = _SOURCE_PASSWORD if not specified
    private $_TARGET_DB       = '';          // The database name of the Target. Default = _SOURCE_DB if not specified

    private $_ERROR_LEVEL    = 0;          // Report the issues with severity of defined and above
    private $_FAIL_LEVEL     = 3;          // Fail on this number or above. Will override the standard output for exit status

    private $_EXCEPTION_CONFIG_FILENAME    	= '';    // The name of the file where all exceptions are existing (classes, functions, tables,...)

    private $_TESTS = array( 'test-flag'     	 => FALSE,
                             'missing-tables'     => FALSE,
                             'missing-fields'     => FALSE,
                             'wrong-attributes'   => FALSE,
                             'must-exist-field'   => FALSE,
                             'must-exist-pk'      => FALSE,
                             'no-default'         => FALSE,
                             'missing-index' 	 => FALSE,
                             'no-index-but-pk'    => FALSE
                            );

    /**
     * Gets the array of arguments and set the command variables
     * @param $commands -> array of arguments
    */
    public function __construct($commands) {
        $this->_TARGET_HOST     = $this->_SOURCE_HOST;
        $this->_TARGET_PORT     = $this->_SOURCE_PORT;
        $this->_TARGET_DB       = $this->_SOURCE_DB;
        $this->_TARGET_USER 	= $this->_SOURCE_USER;
        $this->_TARGET_PASSWORD = $this->_SOURCE_PASSWORD;
        
        $this->setCommands($commands);
    }

    /**
     * Returns the value of the requested command variable
     * @return String
     */
    public function getCommand(){
        return $this->command;
    }

    /**
     * Sets the command variables
     * @param $commands -> array of arguments
     */
    private function setCommand($command){
        $this->command = $command;
    }

    /**
     * Digest the command line options and variables 
     * 
     * @param $variables -> array of options and variables
     */
    public function setCommands($variables){
        foreach ($variables as $key=>$value) {
            if($key>0){
                $temp = array();
                $temp = explode(":", $value);
                switch (trim($temp[0])) {
                    case '--help':
                        $this->setHelp();
                        break;
                        
                    case '--verbose':
                        $this->setVerbose();
                        break;
                        
                    case '-V':
                            $this->setVerbose();
                        break;
                        
                    case '--color':
                        $this->setColor();
                        break;

                    case '-C':
                        $this->setColor();
                        break;
                        
                    case '--sql-fix':
                        $this->setSQLFix();
                        break;

                    case '-S':
                        $this->setSQLFix();
                        break;
                        
                        //=========================================================
                    case '--source-database':
                        $this->checkArgument($temp, 'source-database');
                        break;

                    case '-D':
                        $this->checkArgument($temp, 'source-database');
                        break;
                        
                    case '--source-port':
                        $this->checkArgument($temp, 'source-port');
                        break;

                    case '-P':
                        $this->checkArgument($temp, 'source-port');
                        break;
                        
                    case '--source-host':
                        $this->checkArgument($temp, 'source-host');
                        break;
                        
                    case '-H':
                        $this->checkArgument($temp, 'source-host');
                        break;
                        
                    case '--source-username':
                        $this->checkArgument($temp, 'source-username');
                        break;

                    case '-U':
                        $this->checkArgument($temp, 'source-username');
                        break;
                        
                    case '--source-password':
                        $this->checkArgument($temp, 'source-password');
                        break;
                        
                    case '-W':
                        $this->checkArgument($temp, 'source-password');
                        break;
                        
                        //=========================================================
                    case '--target-database':
                        $this->checkArgument($temp, 'target-database');
                        break;

                    case '-d':
                        $this->checkArgument($temp, 'target-database');
                        break;

                    case '--target-port':
                        $this->checkArgument($temp, 'target-port');
                        break;
                        	
                    case '-p':
                        $this->checkArgument($temp, 'target-port');
                        break;

                    case '--target-host':
                        $this->checkArgument($temp, 'target-host');
                        break;
                        	
                    case '-h':
                        $this->checkArgument($temp, 'target-host');
                        break;
                        	
                    case '--target-username':
                        $this->checkArgument($temp, 'target-username');
                        break;
                        	
                    case '-u':
                        $this->checkArgument($temp, 'target-username');
                        break;
                        	
                    case '--target-password':
                        $this->checkArgument($temp, 'target-password');
                        break;
                        
                    case '-w':
                        $this->checkArgument($temp, 'target-password');
                        break;
                        //=========================================================
                    case '--error-level':
                        $this->checkArgument($temp, 'error-level');
                        break;

                    case '-E':
                        $this->checkArgument($temp, 'error-level');
                        break;
                        
                        
                    case '--fail-level':
                        $this->checkArgument($temp, 'fail-level');
                        break;

                    case '-F':
                        $this->checkArgument($temp, 'fail-level');
                        break;
                        
                    case '--test':
                        $this->checkArgument($temp, 'test');
                        break;
                        
                    case '-T':
                        $this->checkArgument($temp, 'test');
                        break;
                        
                        //=========================================================
                    default:
                        $this->setHelp();
                        break;
                }
            }
        }

        // if target host has not been mentioned assumption is it is same as source host
        if($this->getTargetHost()==''){
            $this->setTargetHost($this->getSourceHost());
        }

        // if target host is same as source host then assumption is it is to use the same username and password of source for target
        if($this->getTargetHost()==$this->getSourceHost()){
            $this->setTargetPWD($this->getSourcePwd());
            $this->setTargetUsrName($this->getSourceUsrName());
        }
    }

    private function checkArgument($temp, $msg_source) {
        if(count($temp)==1){
            echo(functions::getSysMsgSource($msg_source));
            functions::calMaxErrorLevel(_FATAL);
            functions::exitMe();
        }else{
            if(trim($temp[1])==''){
                echo(functions::getSysMsgSource($msg_source, 'error'));
                functions::calMaxErrorLevel(_FATAL);
                functions::exitMe();
            }else{
                switch ($msg_source) {
                    case 'test':
                        $this->setTests($temp[1]);
                        break;
                        	
                    case 'source-database':
                        $this->setSourceDB(trim($temp[1]));
                        break;

                    case 'source-host':
                        $this->setSourceHost(trim($temp[1]));
                        break;
                        	
                    case 'source-port':
                        $this->setSourcePORT(trim($temp[1]));
                        break;

                    case 'source-username':
                        $this->setSourceUsrName(trim($temp[1]));
                        break;

                    case 'source-password':
                        $this->setSourcePWD(trim($temp[1]));
                        break;

                    case 'target-database':
                        $this->setTargetDB(trim($temp[1]));
                        break;

                    case 'target-host':
                        $this->setTargetHost(trim($temp[1]));
                        break;

                    case 'target-port':
                        $this->setTargetPORT(trim($temp[1]));
                        break;

                    case 'target-username':
                        $this->setTargetUsrName(trim($temp[1]));
                        break;

                    case 'target-password':
                        $this->setTargetPWD(trim($temp[1]));
                        break;

                    case 'error-level':
                        $this->setReportLevel(trim($temp[1]));
                        break;

                    case 'fail-level':
                        $this->setFailLevel(trim($temp[1]));
                        break;
                }
            }
        }
    }


    //------------------------------------ EXCEPTION -------------------------------------------------------

    // ------- Test ---------------
    /**
    * Sets the tests
    * @param tests -> array
    *
    */
    private function setTests($tests){
    $tests = explode(",", $tests);
    foreach ($tests as $key => $test) {
    switch (trim($test)) {
				case 'missing-tables':
				$this->_TESTS[$test] = TRUE;
				$this->_TESTS['test-flag'] = TRUE;
				break;

				case 'missing-fields':
				$this->_TESTS[$test] = TRUE;
				$this->_TESTS['test-flag'] = TRUE;
				break;

				case 'wrong-attributes':
				$this->_TESTS[$test] = TRUE;
				$this->_TESTS['test-flag'] = TRUE;
				break;

				case 'must-exist-field':
				$this->_TESTS[$test] = TRUE;
				$this->_TESTS['test-flag'] = TRUE;
				break;

				case 'no-default':
				    $this->_TESTS[$test] = TRUE;
				    $this->_TESTS['test-flag'] = TRUE;
				    break;

				    case 'must-exist-pk':
				    $this->_TESTS[$test] = TRUE;
				    $this->_TESTS['test-flag'] = TRUE;
				    break;

				    case 'missing-index':
				    $this->_TESTS[$test] = TRUE;
				    $this->_TESTS['test-flag'] = TRUE;
				    break;

				case 'no-index-but-pk':
				$this->_TESTS[$test] = TRUE;
				$this->_TESTS['test-flag'] = TRUE;
				    break;

				    default:
				    echo "Test ($test) has not been defined...".PHP_EOL;
				    break;

				    }
				    }
				    }

				    /**
				    * Returns the test array
				    *
				        * @return String
				        */
				        public function getTests(){
				        return $this->_TESTS;
				        }



				        // ------- Fail Level ---------------
				            /**
				            * Sets the Fail level
				            * @param fail_level-> String
				            *
				            */
				            private function setFailLevel($fail_level){ $this->_FAIL_LEVEL = $fail_level;}

				            /**
				            * Returns the report fail value
				            *
				            * @return String
				            */
				            public function getFailLevel(){ return $this->_FAIL_LEVEL;}


				            // ------- Report Level ---------------
				            /**
				            * Sets the Report level
				            * @param error_level-> String
				            *
				            */
				            private function setReportLevel($error_level){ $this->_ERROR_LEVEL = $error_level;}

				            /**
				            * Returns the report level value
				            *
				            * @return String
				            */
				            public function getReportLevel(){ return $this->_ERROR_LEVEL;}


				            // ------- Excepttion file name ---------------
				            /**
				            * Sets the Exception File Name
				            * @param exp_file_name-> String
				            *
				            */
				            private function setExpFileName($exp_file_name){ $this->_EXCEPTION_CONFIG_FILENAME = $exp_file_name;}

				            /**
				            * Returns the Exception File Name value
				            *
				            * @return String
				            */
				            public function getExpFileName(){ return $this->_EXCEPTION_CONFIG_FILENAME;}

				            //------------------------------------ TARGET -------------------------------------------------------

				            // ------- Target Host ---------------
				            /**
				            * Sets the Target Host
				            * @param target_host-> String
				            *
				            */
				            private function setTargetHost($target_host){ $this->_TARGET_HOST = $target_host;}

				            /**
				            * Returns the Target Host value
				            *
				                * @return String
				                */
				                public function getTargetHost(){ return $this->_TARGET_HOST;}

				                // ------- Target Port ---------------
				                /**
				                * Sets the Target Port
				                * @param target_port-> String
				                *
				                */
				                private function setTargetPORT($target_port){ $this->_TARGET_PORT = $target_port;}

				                /**
				                * Returns the Target Port value
				                *
				                * @return String
				                */
				                public function getTargetPort(){ return $this->_TARGET_PORT;}

				                // ------- Target Password ---------------
				                /**
				                * Sets the Target Password
				                * @param target_password-> String
				                *
				                */
				                private function setTargetPWD($target_password){ $this->_TARGET_PASSWORD = $target_password;}

				                /**
				                * Returns the Target Password value
				                *
				                * @return String
				                */
				                public function getTargetPWD(){ return $this->_TARGET_PASSWORD;}

				                // ------- Target Username ---------------
				                /**
				                * Sets the Target Username
				                * @param target_username-> String
				                *
				                */
				                private function setTargetUsrName($target_username){ $this->_TARGET_USER = $target_username;}

				                /**
				                * Returns the Target Username value
				                *
				                 * @return String
				                */
				                public function getTargetUsrName(){ return $this->_TARGET_USER;}
				                	
				                // ------- Target DB ---------------
				                /**
				                * Sets the Target DB
				                * @param target_db-> String
				                *
				                */
				                private function setTargetDB($target_db){ $this->_TARGET_DB = $target_db;}

				                /**
				                * Returns the Target DB value
				                *
				                * @return String
				                */
				                public function getTargetDB(){ return $this->_TARGET_DB; }

				                //------------------------------------ SOURCE -------------------------------------------------------

				                // ------- Source Host ---------------
				                /**
				                * Sets the Source Host
				                * @param source_host-> String
				                *
				                */
				                private function setSourceHost($source_host){ $this->_SOURCE_HOST = $source_host;}

				                /**
				                * Returns the Source Host value
				                *
				                * @return String
				                */
				                public function getSourceHost(){ return $this->_SOURCE_HOST;}

				                // ------- Source Port ---------------
				                /**
				                * Sets the Source Port
				                * @param source_Port-> String
				                *
				                */
				                private function setSourcePORT($source_port){ $this->_SOURCE_PORT = $source_port;}

				                /**
				                * Returns the Source Port value
				                *
				                * @return String
				                */
				                public function getSourcePort(){ return $this->_SOURCE_PORT;}

				                // ------- Source Password ---------------
				                /**
				                * Sets the Source Password
				                * @param source_password-> String
				                *
				                */
	private function setSourcePWD($source_password){ $this->_SOURCE_PASSWORD = $source_password;}

	/**
	 * Returns the Source Password value
	 *
	 * @return String
	 */
	public function getSourcePWD(){ return $this->_SOURCE_PASSWORD;}

	// ------- Source Username ---------------
	/**
	 * Sets the Source Username
	 * @param source_username-> String
	 *
	 */
	private function setSourceUsrName($source_username){ $this->_SOURCE_USER = $source_username;}

	/**
	 * Returns the Source Username value
	 *
	 * @return String
	 */
	public function getSourceUsrName(){ return $this->_SOURCE_USER;}
		
// ------- Source DB ---------------
	/**
	 * Sets the Source DB
	 * @param source_db-> String
	 *
	 */
	private function setSourceDB($source_db){ $this->_SOURCE_DB = $source_db;}

	/**
	 * Returns the Source DB value
	 *
	 * @return String
	 */
	public function getSourceDB(){ return $this->_SOURCE_DB;}

//------------------------------------ GENERAL -------------------------------------------------------

	// ------- Help ---------------
	/**
	 * Sets the Help flag
	 * @param flag-> Boolean (True/False)
	 *
	 */
	private function setHelp($flag=TRUE){ $this->_HELP = $flag;}

	/**
	 * Returns the Help value
	 *
	 * @return Boolean
	 */
	public function getHelp(){ return $this->_HELP;}

	// ------- Color ---------------
	/**
	 * Sets the Color flag
	 * @param flag-> Boolean (True/False)
	 *
	 */
	private function setColor($flag=TRUE){ $this->_COLOR = $flag;}

	/**
	 * Returns the Color value
	 *
	 * @return Boolean
	 */
	public function getColor(){ return $this->_COLOR;}

// ------- Verbose ---------------
	/**
	 * Sets the Verbose flag
	 * @param flag-> Boolean (True/False)
	 *
	 */
	private function setVerbose($flag=TRUE){ $this->_VERBOSE = $flag;}

	/**
	 * Returns the Verbose value
	 *
	 * @return Boolean
	 */
	public function getVerbose(){ return $this->_VERBOSE;}

// ------- SQL Fix ---------------
	/**
	 * Sets the sqlffix flag
	 * @param flag-> Boolean (True/False)
	 *
	 */
	private function setSQLFix($flag=TRUE){ $this->_SQLFIX = $flag;}

	/**
	 * Returns the Sql Fix value
	 *
	 * @return Boolean
	 */
	public function getSQLFix(){ return $this->_SQLFIX;}
}
