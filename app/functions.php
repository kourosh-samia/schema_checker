<?php
class functions {
    private $config;
    static private $max_exist_status = 0;

    public function __construct($type='app'){
        if($type=='app'){
            die('unknown $type 1111111111111');
            $this->setConfig(App_Config::getAppConfig());
        }elseif($type=='exp'){
            die('unknown $type 2222222222222');
            $this->setConfig(getExceptionConfig());
        }elseif($type=='rules'){
            $this->setConfig(App_Config_RulesConfig::getRulesConfig());
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

    public static function getRulesConfig(){
        $self = new App_Functions('rules');
        $temp = $self->getConfig();
        return ($temp);
    }

    //----------------------------------- EXCEPTION Config --------------------------------------------------


    public static function getEXPTargetTables(){
        $self = new App_Functions('exp');
        $temp = $self->getConfig();
        return ($temp['except_target_tables']);
    }

    public static function getEXPIndexs(){
        $self = new App_Functions('exp');
        $temp = $self->getConfig();
        return ($temp['except_index']);
    }

    public static function getMissingFieldsEXP(){
        $self = new App_Functions('exp');
        $temp = $self->getConfig();
        return ($temp['missing_fields_exception']);
    }

    public static function getMustExistFields(){
        $self = new App_Functions('exp');
        $temp = $self->getConfig();
        return ($temp['should_exist_fields_in_target_tables']);
    }

    public static function getMustExistPK(){
        $self = new App_Functions('exp');
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
        $self = new App_Functions();
        $temp = $self->getConfig();
        return ($temp['system-messages'][$topic][$type].PHP_EOL);
    }

    //----------------------------------- GENERAL --------------------------------------------------
    /**
    * calculate the maximum error level. If the current $max_exist_status smaller
    * then passed on the error level then it will be set to new value
    * @param Int error_level-> passed on error level
    * @param Boolean $exitme -> If ture the exit with $max_exist_status
    */
    public static function calMaxErrorLevel($error_level, $exitme=FALSE, $fail_level=3){
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

    /*
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
        return array('length'=>$max_length,
        'data'=>$max_string);
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

}