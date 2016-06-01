<?php
class functions {
    private $config;
    static private $max_exist_status = 0;

//@todo find out what is the purpose of the type here    
    /**
     * 
     * @param string $type
     */
    public function __construct($type='app'){
        if($type=='app'){
            die('unknown $type 1111111111111');
            $this->setConfig(config::getAppConfig());
        }elseif($type=='exp'){
            die('unknown $type 2222222222222');
            $this->setConfig(getExceptionConfig());
            
        }elseif($type=='rules'){
            $this->setConfig(rules::getExceptionRules());
        }
    }

    private function setConfig($config){
        $this->config = $config;
        return $this;
    }

    public function getConfig(){
        return $this->config;
    }

    //----------------------------------- Rules Config --------------------------------------------------
//GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG
    /**
     * Returns a function object 
     */
    public static function getExceptionRules(){
        $self = new functions('rules');
        return ($self->getConfig());
    }

    //----------------------------------- EXCEPTION Config --------------------------------------------------


    public static function getEXPTargetTables(){
        $self = new functions('exp');
        $temp = $self->getConfig();
        return ($temp['except_target_tables']);
    }

    public static function getEXPIndexs(){
        $self = new functions('exp');
        $temp = $self->getConfig();
        return ($temp['except_index']);
    }

    public static function getMissingFieldsEXP(){
        $self = new functions('exp');
        $temp = $self->getConfig();
        return ($temp['missing_fields_exception']);
    }

    public static function getMustExistFields(){
        $self = new functions('exp');
        $temp = $self->getConfig();
        return ($temp['should_exist_fields_in_target_tables']);
    }

    public static function getMustExistPK(){
        $self = new functions('exp');
        $temp = $self->getConfig();
        return ($temp['must_exist_PK'][0]);
    }


    //----------------------------------- APP Messages --------------------------------------------------

    /**
     * Returns the system messages
     * @param $topic -> String name of the topic you want to get the message about
     * @param $type  -> String The type of the message you want to get (error, warning,...)
     * @return String The requested message
     */
    public static function getSysMsgSource($topic, $type='warning'){
        $self = new functions();
        $temp = $self->getConfig();
        return ($temp['system-messages'][$topic][$type].PHP_EOL);
    }

    
    //----------------------------------- GENERAL --------------------------------------------------
    
//GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG
   /**
    * This function calculates the maximum error level. 
    * If the current $max_exist_status smaller then 
    * passed on the error level then it will be set 
    * to new value
    * 
    * @param Int error_level -> passed on error level
    * @param Boolean $exitme -> If ture the exit with $max_exist_status
    */
    public static function calMaxErrorLevel($error_level, $exitme = FALSE, $fail_level = 3){
        if(self::$max_exist_status < $error_level){
            self::$max_exist_status = $error_level;
        }

        if($exitme){
            if (self::$max_exist_status>=$fail_level) {
                exit(self::$max_exist_status);
            }else{
                exit(0);
            }
        }
     }

   /**
    * retunrs the Maximum error level
    * @param Init $max_exist_status
    */
    public static function getMaxErrorLevel(){
     return self::$max_exist_status;
    }

   /**
    * exit the prgram with max defined exist status
    *
    */
    public static function exitMe($fail_level){
        if (self::$max_exist_status>=$fail_level) {
            exit(self::$max_exist_status);
        }else{
            exit(0);
         }
    }

   /**
    * calculate the maximum length of an array of string and returns the longest one back
    * @param array $data -> array of strings
    * @return Int
    */
    public static function getMaxLength(array $data){
        $max_length = 0;
        $max_string = '';
        foreach ($data as $key=>$value) {
            if(strlen($value)>$max_length){
                $max_length=strlen($value);
                $max_string =$value;
            }
        }
        return array('length'=>$max_length,'data'=>$max_string);
    }

   /**
    * format the output and add proper character to the end of string
    * @param $data -> String input string
    * @param $space -> Int number of charachters should be added
    * @param $filler_char -> String What charachter should be used as filler
    * @param $right_left_both -> STR_PAD_RIGHT , STR_PAD_LEFT, STR_PAD_BOTH
    * @param $return_char -> Boolean if return char should be added or not
    *
    * @return String
    */
    public static function formatOutput($data, $space, $filler_char=' ', $right_left_both=STR_PAD_RIGHT, $return_char = FALSE){
    	$temp = str_pad($data, $space, $filler_char, $right_left_both);
    	if($return_char){
    		return $temp.PHP_EOL;
    	}else{
    		return $temp;
    	}
	}

	public static function getClass($data){
		print_r($data);
	}

//GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG	
    /**
     * This function will generate a header for the schema checker
     *
     * @param array $info -> The array of all server variables needed to generate the header
     * @return string -> parsed header will be returned
     */
	 public static function parseOutoutHeader($info){
        // to find the maximum lenght of charachers in a line and use it to adjust the header output
        $max_length = self::getMaxLength(array( "Host: {$info['source_host']}",
                                                "Port: {$info['source_port']}",
                                                "Database: {$info['source_db']}",
                                                "Username: {$info['source_username']}",
                                                "Password: {$info['source_password']}"
                                                )
                                        );
        
        $header = PHP_EOL."-----------------------------------------------------------------------------------------------------------------".PHP_EOL.
        self::formatOutput("Host: {$info['source_host']}",         $max_length['length']+5)."Host: {$info['target_host']}".PHP_EOL.
        self::formatOutput("Port: {$info['source_port']}",         $max_length['length']+5)."Port: {$info['target_port']}".PHP_EOL.
        self::formatOutput("Database: {$info['source_db']}",       $max_length['length']+5)."Database: {$info['target_db']}".PHP_EOL.
        self::formatOutput("Username: {$info['source_username']}", $max_length['length']+5)."Username: {$info['target_username']}".PHP_EOL.
        self::formatOutput("Password: {$info['source_password']}", $max_length['length']+5)."Password: {$info['target_password']}".PHP_EOL.
        self::formatOutput("  ______",     $max_length['length'] + 5)." ______".PHP_EOL.
        self::formatOutput(" /      \ ",   $max_length['length'] + 5)."/      \ ".PHP_EOL.
        self::formatOutput("|\_____ /|",   $max_length['length'] + 4)."|\______/|".PHP_EOL.
        self::formatOutput("|        | <", $max_length['length']+1 ,"-").">  |        |".PHP_EOL.
        self::formatOutput("| Source |",   $max_length['length'] + 4)."| Target |".PHP_EOL.
        self::formatOutput(" \______/",    $max_length['length'] + 5)."\______/".PHP_EOL.
        "-----------------------------------------------------------------------------------------------------------------".PHP_EOL.PHP_EOL;
        return $header;
    } 

}