<?php
/* 
__________________________________________________________________________________ 
You can set the rules in this file. 
    1. If you have developed a new test, you can add it to the default array.
       Also, you can turn a test on or off. This is helpful when you want to 
       run the schema checker for all tests but, don't want to mention them 
       one by one in the command line.
       
    2. You can turn the test on or off for a specific database you have. 
       Basically, you can override the default test as well.
       
    3. You can set verbosity and exceptions for each test on every database 
       or two databases that needs to be tested for schema validation.
__________________________________________________________________________________ 

##################################################################################       
In getTestConfig() you can set the default tests or override them. Below example 
shows the summary of override

     missing tables	missing fields	missing index  wrong attributes must-exist-field  must-exist-pk  no-default  no-index-but-pk
db1		    YES			YES				   YES			YES               NO               NO            NO             NO			
db2		    YES			YES				   YES			YES               NO               YES           YES            YES			
db3		    YES			YES				   YES			YES               NO               NO            NO             NO			
db4		    YES			YES				   NO			NO                NO               NO            NO             NO			
##################################################################################       

In getRulesConfig() you can set your exceptions for tests and set the verbosity 
for each exception.

We can set the exception rules at database level, table level, field level and index level.
We set the exception rules on the source database and target database can be the same or a totally different database.
Each exception can have its own verbosity. You can not have the expection but still set the verbosity on each test.
Verbosity defines that at what level the exit status will be error or at what level the output should be shown on screen as error.   

The rule structure will lokk like below 
source db::table_name::field_name::index_name | target db | test | Severity level

array('db1::*::*::*'    ,'db2' , 'MissingTable' ,_GOOD),
array('db2::*::*::*'    ,'db1' ,'MissingField'  ,_ERROR), 
array('db3::*::*::*'    ,'db4' ,'MissingIndex'  ,_FATAL), 

Severity levels;
-----------------------
_GOOD'     ,0
_INFO'     ,1    white
_WARNING'  ,2    yellow
_ERROR'    ,3    purple
_FATAL'    ,4    red


*/ 


/*
 * $key is actualy the target_db_name. If it finds it, the test_rules will be returned otherwise the default
 * if in the command line you dont use --test:... then the the test rules will be read from here
 * but if you use --test switch with specific arguments, then if those arguments been activated in the test_rules then they will
 * get run otherwise they wont.
 */



class rules{
    
    /**
     * You can set the default tests here. 
     * schema checker needs to know by 
     * default which tests are on or off
     * 
     * @param String $db_tests -> This is the name of the database 
     *                            which you set the rules to override 
     *                            the validation tests
     * @return Array           -> An array of tests and their status 
     *                            will be returned                           
     */
    public static function getTestConfig($db_tests){
        
        // So far we have developed tests as below
        $default = array(
                        'missing-tables'    => TRUE,
                        'missing-fields'    => TRUE,
                        'missing-index'     => TRUE,
                        'wrong-attributes'  => TRUE,
                        'must-exist-field'  => TRUE,
                        'must-exist-pk'     => TRUE,
                        'no-default'        => TRUE,
                        'no-index-but-pk'   => TRUE
                        );
        // Here you can override the default tests for every database you have 
        $override_rules = array(
                                'db1'=>array(
                                            'missing-tables'         => TRUE,
                                            'missing-fields'         => TRUE,
                                            'missing-index'          => TRUE,
                                            'wrong-attributes'       => TRUE,
                                            'must-exist-field'       => FALSE,
                                            'must-exist-pk'          => FALSE,
                                            'no-default'             => FALSE,
                                            'no-index-but-pk'        => FALSE
                                            ),
                            
                                'db2'=>array(
                                            'missing-tables'         => TRUE,
                                            'missing-fields'         => TRUE,
                                            'missing-index'          => TRUE,
                                            'wrong-attributes'       => TRUE,
                                            'must-exist-field'       => FALSE,
                                            'must-exist-pk'          => TRUE,
                                            'no-default'             => TRUE,
                                            'no-index-but-pk'        => TRUE
                                            ),
                            
                                'db3'=>array(
                                            'missing-tables'     => TRUE,
                                            'missing-fields'     => TRUE,
                                            'missing-index'      => TRUE,
                                            'wrong-attributes'   => TRUE,
                                            'must-exist-field'   => FALSE,
                                            'must-exist-pk'      => FALSE,
                                            'no-default'         => FALSE,
                                            'no-index-but-pk'    => FALSE
                                            ),
                            
                                'db4'=>array(
                                            'missing-tables'     => TRUE,
                                            'missing-fields'     => TRUE,
                                            'missing-index'      => FALSE,
                                            'wrong-attributes'   => FALSE,
                                            'must-exist-field'   => FALSE,
                                            'must-exist-pk'      => FALSE,
                                            'no-default'         => FALSE,
                                            'no-index-but-pk'    => FALSE
                                            )    	
        );

        // If the tests for specific database was asked then the appropriate array  
        // will be returned otherwise the default array tests will be sent back
        if(array_key_exists(trim($db_tests), $override_rules)){
            $test_rules[$db_tests]['test-flag'] = FALSE;
            return $test_rules[$db_tests];
        }else{
            $default['test-flag'] = FALSE;
            return $default;
        }
    }     

    /**
     * This will return the array of exception rules back
     * 
     * @return Array -> array of exeptions
     */
    public static function getExceptionRules(){
        $user_rules = array(
                            // db1 vs db2
                            //------------------------------------------------------
                            array('db1::*'                ,'db2' ,'MissingTable'    ,VER__GOOD),
                            array('db1::*::*'             ,'db2' ,'MissingField'    ,VER__ERROR),
                            array('db::*::*::*'           ,'db2' ,'MissingIndex'    ,VER__GOOD),
                            array('db1::*::*::*'          ,'db2' ,'DATA_TYPE'       ,VER__FATAL),
                            array('db1::*::*::*'          ,'db2' ,'COLLATION_NAME'  ,VER__FATAL),
                            array('db1::*::*::*'          ,'db2' ,'IS_NULLABLE'     ,VER__FATAL),
                            array('db1::*::*::*'          ,'db2' ,'COLUMN_DEFAULT'  ,VER__FATAL),
                            array('db1::*::*::*'          ,'db2' ,'COLUMN_TYPE'     ,VER__FATAL),
                            array('db1::*::*::merge_id'   ,'db2' ,'MustExistPK'     ,VER__FATAL),
                            array('db1::*::*::*'          ,'db2' ,'NoIndexButPK'    ,VER__FATAL),
                            array('db1::*::merge_id'      ,'db2' ,'MustExistField'  ,VER__ERROR),
                           );
       return $user_rules;        	
    }    
}