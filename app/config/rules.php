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
In getTestConfig you can set the default tests or override them. Below example 
shows the summary of override

     missing tables	missing fields	missing index  wrong attributes must-exist-field  must-exist-pk  no-default  no-index-but-pk
db1		    YES			YES				   YES			YES               NO               NO            NO             NO			
db2		    YES			YES				   YES			YES               NO               YES           YES            YES			
db3		    YES			YES				   YES			YES               NO               NO            NO             NO			
db4		    YES			YES				   NO			NO                NO               NO            NO             NO			
##################################################################################       


*/ 
       
class App_Config_RulesConfig{
    
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

    public static function getRulesConfig(){
        
    }    
    
}
       


    
Testing schema checking and Rules							
								missing tables	missing fields	missing index	wrong attributes	must_exist_pk	must_exist_field	no default
Lms2prod - > user_merge			ok				ok				ok				ok					ok				ok					NA
Lms2prod - > lms2archive		ok				ok				ok				ok					NA				NA					ok
Lms2prod - > lms2delta			ok				ok				ok				ok					NA				NA					ok
sitel_feed  - > sitel_feed_temp	ok				ok				ok				ok					NA				NA					NA



//_GOOD',0
//_INFO'   ,1   // white
//_WARNING',2   // yellow
//_ERROR'  ,3   // purple
//_FATAL'  ,4   // red
	
	/*
	 * $key is actualy the target_db_name. If it finds it, the test_rules will be returned otherwise the default
	 * if in the command line you dont use --test:... then the the test rules will be read from here
	 * but if you use --test switch with specific arguments, then if those arguments been activated in the test_rules then they will 
	 * get run otherwise they wont.
	 */
	
	public static function getRulesConfig(){
//		source::table_name::field_name::index_name     | target      | error type      | Severity level
		$user_rulz=array(
		
/**
 * USER_MERGE
 * ------------
 * Rules:
 * ---------------------------------------------------------------
 * 1. Find missing tables in Source (lms2prod) and Target (user_merge) DB.
 * 2- Missing fields should be reported in both DBs
 * 3- Attributes should be the same as lms2prod
 * 4- PK = PK (lms2prod) + merge_id
 * 5- merge_id field in user_merge should exist
 * 6- merge_id index in user_merge should exist
 * 7- The only index in user_merge should be lms2prodz PK + merge_id and no other index should exist - NoIndexButPK
 * 8- There should be no default in user_merge exceptions are date_created and date_modified
 **/		
		
	// LMS2PROD in USER_MERGE
	//------------------------------------------------------
	  		array('lms2prod::*'                        ,'user_merge' ,'MissingTable'    ,_GOOD), 
		    
	  		array('lms2prod::*::*'                     ,'user_merge' ,'MissingField'    ,_ERROR), 
			
			array('lms2prod::*::*::*'                  ,'user_merge' ,'MissingIndex'    ,_GOOD), 

			array('lms2prod::*::*::*'                  ,'user_merge' ,'DATA_TYPE'       ,_FATAL),  
			array('lms2prod::*::*::*'                  ,'user_merge' ,'COLLATION_NAME'  ,_FATAL),  
			array('lms2prod::*::*::*'                  ,'user_merge' ,'IS_NULLABLE'     ,_FATAL),  
			array('lms2prod::*::*::*'                  ,'user_merge' ,'COLUMN_DEFAULT'  ,_FATAL),  
			array('lms2prod::*::*::*'                  ,'user_merge' ,'COLUMN_TYPE'     ,_FATAL),  
			
			array('lms2prod::*::*::merge_id'           ,'user_merge' ,'MustExistPK'     ,_FATAL),   
			
			array('lms2prod::*::*::*'           	   ,'user_merge' ,'NoIndexButPK'    ,_FATAL),   
			
			array('user_merge::*::merge_id'            ,'user_merge' ,'MustExistField'  ,_ERROR),   
			
	// USER_MERGE  in LMS2PROD		
	//------------------------------------------------------
			array('user_merge'                         ,'lms2prod'   ,'MissingTable'    ,_FATAL), 
			array('user_merge::merge'                  ,'lms2prod'   ,'MissingTable'    ,_GOOD), 
		    
			array('user_merge::*::*'                   ,'lms2prod'   ,'MissingField'    ,_ERROR), 
		    array('user_merge::*::merge_id'            ,'lms2prod'   ,'MissingField'    ,_GOOD), 
			
			array('user_merge::*::*'      			   ,'user_merge' ,'NoDefault'       ,_ERROR),
			array('user_merge::*::*::date_modified'    ,'user_merge' ,'NoDefault'       ,_GOOD),
			array('user_merge::*::*::date_created'     ,'user_merge' ,'NoDefault'       ,_GOOD),
			
			array('user_merge::*::*::merge_id'         ,'lms2prod'   ,'MissingIndex'    ,_GOOD),
			array('user_merge::*::*::*'                ,'lms2prod'   ,'MissingIndex'    ,_WARNING),
			
			
	// USER_MERGE in USER_MERGE
	//------------------------------------------------------
			array('user_merge::*::*::*'                  ,'user_merge' ,'DATA_TYPE'       ,_FATAL),  	
			array('user_merge::*::*::*'                  ,'user_merge' ,'COLLATION_NAME'  ,_FATAL),  	
			array('user_merge::*::*::*'                  ,'user_merge' ,'IS_NULLABLE'     ,_FATAL),  	
			array('user_merge::*::*::*'                  ,'user_merge' ,'COLUMN_DEFAULT'  ,_FATAL),  	
			array('user_merge::*::*::*'                  ,'user_merge' ,'COLUMN_TYPE'     ,_FATAL),  	
			array('user_merge::*::*'                     ,'user_merge' ,'MissingField'    ,_FATAL),	 
			array('user_merge::*::*'                     ,'user_merge' ,'MissingTable'    ,_FATAL),	 
				

			
/**
 * LMS2GROUPS
 * ------------
 * Rules:
 * ---------------------------------------------------------------
 * 1. Find missing tables in Source (lms2prod) and Target (user_merge) DB.
 * 2- Missing fields should be reported in both DBs
 * 3- Attributes should be the same as lms2prod
 * 4- PK = PK (lms2prod) + merge_id
 * 5- merge_id field in user_merge should exist
 * 6- merge_id index in user_merge should exist
 * 7- The only index in user_merge should be lms2prodz PK + merge_id and no other index should exist - NoIndexButPK
 * 8- There should be no default in user_merge exceptions are date_created and date_modified
 * 9- Checks the schema of same database in two different servers

 **/		
		
	// LMS2GROUPS  in USER_MERGE		
	//------------------------------------------------------
	  		array('lms2groups::*'                        ,'user_merge' ,'MissingTable'    ,_FATAL), 
			
	  		array('lms2groups::*::*'                     ,'user_merge' ,'MissingField'    ,_ERROR), 
			
			array('lms2groups::*::*::*'                  ,'user_merge' ,'MissingIndex'    ,_FATAL), 

			array('lms2groups::*::*::*'                  ,'user_merge' ,'DATA_TYPE'       ,_FATAL),  
			array('lms2groups::*::*::*'                  ,'user_merge' ,'COLLATION_NAME'  ,_FATAL),  
			array('lms2groups::*::*::*'                  ,'user_merge' ,'IS_NULLABLE'     ,_FATAL),  
			array('lms2groups::*::*::*'                  ,'user_merge' ,'COLUMN_DEFAULT'  ,_FATAL),  
			array('lms2groups::*::*::*'                  ,'user_merge' ,'COLUMN_TYPE'     ,_FATAL),  
			
			array('lms2groups::*::*::merge_id'           ,'user_merge' ,'MustExistPK'     ,_FATAL),   
			
			array('lms2groups::*::*::*'           	     ,'user_merge' ,'NoIndexButPK'    ,_FATAL),   
			
			array('lms2groups::*::merge_id'              ,'user_merge' ,'MustExistField'  ,_ERROR),   
			
	// USER_MERGE in LMS2GROUPS
	//------------------------------------------------------
			array('user_merge'                         ,'lms2groups'   ,'MissingTable'    ,_GOOD), 

			array('user_merge::*::merge_id'            ,'lms2groups'   ,'MissingField'    ,_GOOD), 
			array('user_merge::*::*'                   ,'lms2groups'   ,'MissingField'    ,_ERROR), 
			
			array('user_merge::*::*'      			   ,'user_merge' ,'NoDefault'       ,_ERROR),
			array('user_merge::*::*::date_modified'    ,'user_merge' ,'NoDefault'       ,_GOOD),
			array('user_merge::*::*::date_created'     ,'user_merge' ,'NoDefault'       ,_GOOD),
			
			array('user_merge::*::*::merge_id'         ,'lms2groups'   ,'MissingIndex'    ,_GOOD),
			array('user_merge::*::*::*'                ,'lms2groups'   ,'MissingIndex'    ,_ERROR),
			
	// LMS2GROUPS in LMS2GROUPS
	//------------------------------------------------------
			array('lms2groups::*::*::*'                  ,'lms2groups' ,'DATA_TYPE'       ,_FATAL),  	
			array('lms2groups::*::*::*'                  ,'lms2groups' ,'COLLATION_NAME'  ,_FATAL),  	
			array('lms2groups::*::*::*'                  ,'lms2groups' ,'IS_NULLABLE'     ,_FATAL),  	
			array('lms2groups::*::*::*'                  ,'lms2groups' ,'COLUMN_DEFAULT'  ,_FATAL),  	
			array('lms2groups::*::*::*'                  ,'lms2groups' ,'COLUMN_TYPE'     ,_FATAL),  	
			array('lms2groups::*::*'                     ,'lms2groups' ,'MissingField'    ,_FATAL),	 
			array('lms2groups::*::*'                     ,'lms2groups' ,'MissingTable'    ,_FATAL),	 
			
			
			
/**
 * LMS2ARCHVIE
 * ------------
 *
 * Rules:
 * ---------------------------------------------------------------
 * 1. Find missing tables in Source (lms2prod) and Target (lms2archive) DB.
 * 2- Missing fields should be reported in both DBs
 * 3- Attributes should be the same as lms2prod
 * 4- PK = PK (lms2prod)
 * 5- Fields of lms2archive should not have default value at all
 * 6- The only index in lms2archive should be lms2prodz PK and no other index should exist - NoIndexButPK
  **/
//		source::table_name::field_name::index_name     | target      | error type      | Severity level
			
	// LMS2PROD in LMS2ARCHIVE
	//------------------------------------------------------
			array('lms2prod::*'            ,'lms2archive' ,'MissingTable'    ,_GOOD), 
			
			array('lms2prod::*::*'         ,'lms2archive' ,'MissingField'    ,_FATAL), 
			
			array('lms2prod::*::*::*'      ,'lms2archive' ,'MissingIndex'    ,_ERROR),
			
			array('lms2prod::*::*::*'      ,'lms2archive' ,'DATA_TYPE'       ,_FATAL),  
			array('lms2prod::*::*::*'      ,'lms2archive' ,'COLLATION_NAME'  ,_FATAL),  
			array('lms2prod::*::*::*'      ,'lms2archive' ,'IS_NULLABLE'     ,_FATAL),  
			array('lms2prod::*::*::*'      ,'lms2archive' ,'COLUMN_DEFAULT'  ,_GOOD),  
			array('lms2prod::*::*::*'      ,'lms2archive' ,'COLUMN_TYPE'     ,_FATAL),  
			
			array('lms2prod::*::*::*'      ,'lms2archive' ,'NoIndexButPK'    ,_FATAL),   
//			array('lms2prod::course::*::*'      ,'lms2archive' ,'NoIndexButPK'    ,_WARNING),   
			
			array('lms2archive::*::*'      ,'lms2archive' ,'NoDefault'       ,_WARNING),   
//			array('lms2archive::*::*::status'      ,'lms2archive' ,'NoDefault'       ,_INFO),   
			
	// LMS2ARCHIVE in LMS2PROD		
	//------------------------------------------------------
			array('lms2archive'            ,'lms2prod'    ,'MissingTable'    ,_INFO), 

			array('lms2archive::*::*'      ,'lms2prod'    ,'MissingField'    ,_ERROR), 

			array('lms2archive::*::*::*'   ,'lms2prod'    ,'MissingIndex'    ,_WARNING),
			
	// LMS2ARCHIVE in LMS2ARCHIVE
	//------------------------------------------------------
			array('lms2archive::*::*::*'   ,'lms2archive' ,'DATA_TYPE'       ,_FATAL),  	
			array('lms2archive::*::*::*'   ,'lms2archive' ,'COLLATION_NAME'  ,_FATAL),  	
			array('lms2archive::*::*::*'   ,'lms2archive' ,'IS_NULLABLE'     ,_FATAL),  	
			array('lms2archive::*::*::*'   ,'lms2archive' ,'COLUMN_DEFAULT'  ,_FATAL),  	
			array('lms2archive::*::*::*'   ,'lms2archive' ,'COLUMN_TYPE'     ,_FATAL),  	
			array('lms2archive::*::*'      ,'lms2archive' ,'MissingField'    ,_FATAL),	 
			array('lms2archive::*::*'      ,'lms2archive' ,'MissingTable'    ,_FATAL),	 
	
//SITEL_FEED and SITEL_FEED_TEMP			
/**
 * source_tables are the tables from sitel_feed which their tables should be
 * checked against lms2prod. Only table in config file will be ignored.
 *
 * Rules:
 * ---------------------------------------------------------------
 * 1. Find the missing tables in Source DB (tables which exist in sitel_feed_temp but not in source- in this case source is sitel_feed) - the 
 * 	  exception tables in config file
 * 2. Find the missing tables in Target DB (tables which exist in sitel_feed but not in source- in this case target is sitel_feed_temp) - the 
 * 	  exception tables in config file
 * 3- missing fields should be reported
 * 4- Field_name, data_type should be the same as lms2prod
 **/	

			
	// SITEL_FEED in SITEL_FEED_TEMP
	//------------------------------------------------------
			array('sitel_feed::*'               		,'sitel_feed_temp'   ,'MissingTable'   ,_WARNING),  
			array('sitel_feed::client_employee_archive' ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::client_employee_temp'    ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::dep_map'                 ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::duplicate_check'         ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::emp_map'                 ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::employee_mv'             ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::feed_checker_rules'      ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::feed_file_info'          ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::feed_source_mapper'      ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::feed_stat'               ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::lut_sitel_feed_field'    ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::que'                     ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::source'                  ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::term'                    ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			array('sitel_feed::temp_data'               ,'sitel_feed_temp'   ,'MissingTable'   ,_GOOD),  
			
			array('sitel_feed::*::*'                    ,'sitel_feed_temp'   ,'MissingField'   ,_FATAL),  

			array('sitel_feed::*::*::*'                 ,'sitel_feed_temp'   ,'DATA_TYPE'      ,_FATAL),  
			array('sitel_feed::*::*::*'                 ,'sitel_feed_temp'   ,'COLLATION_NAME' ,_FATAL),  
			array('sitel_feed::*::*::*'                 ,'sitel_feed_temp'   ,'IS_NULLABLE'    ,_FATAL),  
			array('sitel_feed::*::*::*'                 ,'sitel_feed_temp'   ,'COLUMN_DEFAULT' ,_FATAL),  
			array('sitel_feed::*::*::*'                 ,'sitel_feed_temp'   ,'COLUMN_TYPE'    ,_FATAL),  
			
			array('sitel_feed::*::*::*'                 ,'sitel_feed_temp'   ,'MissingIndex'   ,_INFO),
			
			
	// SITEL_FEED_TEMP in SITEL_FEED
	//------------------------------------------------------
			array('sitel_feed_temp::*'                  ,'sitel_feed'        ,'MissingTable'   ,_WARNING),
			array('sitel_feed_temp::*::*'               ,'sitel_feed'        ,'MissingField'   ,_FATAL),  
			array('sitel_feed_temp::*::*::*'            ,'sitel_feed'        ,'MissingIndex'   ,_INFO),

			
	// SITEL_FEED in SITEL_FEED
	//------------------------------------------------------
			array('sitel_feed::*::*::*'                  ,'sitel_feed' ,'DATA_TYPE'       ,_FATAL),  	
			array('sitel_feed::*::*::*'                  ,'sitel_feed' ,'COLLATION_NAME'  ,_FATAL),  	
			array('sitel_feed::*::*::*'                  ,'sitel_feed' ,'IS_NULLABLE'     ,_FATAL),  	
			array('sitel_feed::*::*::*'                  ,'sitel_feed' ,'COLUMN_DEFAULT'  ,_FATAL),  	
			array('sitel_feed::*::*::*'                  ,'sitel_feed' ,'COLUMN_TYPE'     ,_FATAL),  	
			array('sitel_feed::*::*'                     ,'sitel_feed' ,'MissingField'    ,_FATAL),	 
			array('sitel_feed::*::*'                     ,'sitel_feed' ,'MissingTable'    ,_FATAL),
				 
	// SITEL_FEED_TEMP in SITEL_FEED_TEMP
	//------------------------------------------------------
			array('sitel_feed_temp::*::*::*'             ,'sitel_feed_temp' ,'DATA_TYPE'       ,_FATAL),  	
			array('sitel_feed_temp::*::*::*'             ,'sitel_feed_temp' ,'COLLATION_NAME'  ,_FATAL),  	
			array('sitel_feed_temp::*::*::*'             ,'sitel_feed_temp' ,'IS_NULLABLE'     ,_FATAL),  	
			array('sitel_feed_temp::*::*::*'             ,'sitel_feed_temp' ,'COLUMN_DEFAULT'  ,_FATAL),  	
			array('sitel_feed_temp::*::*::*'             ,'sitel_feed_temp' ,'COLUMN_TYPE'     ,_FATAL),  	
			array('sitel_feed_temp::*::*'                ,'sitel_feed_temp' ,'MissingField'    ,_FATAL),	 
			array('sitel_feed_temp::*::*'                ,'sitel_feed_temp' ,'MissingTable'    ,_FATAL),	 
			
			
/**
 * LMS2DELTA
 * ------------
 * Rules:
 * ---------------------------------------------------------------
 * 1. Find missing tables in Source (lms2prod) and Target (lms2delta) DB.
 * 2- Missing fields should be reported in both DBs
 * 3- Attributes should be the same as lms2prod
 * 4- PK = PK (lms2prod) + date_stored
 * 5- date_stored, delta_type, delta_note, delta_rbac_user_id fields in lms2archive should exist
 * 6- date_stored index should exist in lms2delta
 * 7- Fields of lms2delta should not have default value except date_stored, delta_type, delta_note, delta_rbac_user_id fields
 * 8- The only index in lms2delta should be lms2prodz PK + date_stored and no other index should exist - NoIndexButPK 
 **/		
			
			
	// LMS2PROD in LMS2DELTA
	//------------------------------------------------------
	  		array('lms2prod::*'                        ,'lms2delta' ,'MissingTable'    ,_GOOD), 	// Script Passed - SQL FIX Passed
			
	  		array('lms2prod::*::*'                     ,'lms2delta' ,'MissingField'    ,_FATAL), 	// Script Passed - SQL FIX Passed
			
			array('lms2prod::*::*::*'                  ,'lms2delta' ,'MissingIndex'    ,_ERROR), 	// Script Passed - SQL FIX Passed
																									// #### SQL fix should be developed
			
			array('lms2prod::*::*::*'                  ,'lms2delta' ,'DATA_TYPE'       ,_FATAL),  	//checked
			array('lms2prod::*::*::*'                  ,'lms2delta' ,'COLLATION_NAME'  ,_FATAL),  	//checked
			array('lms2prod::*::*::*'                  ,'lms2delta' ,'IS_NULLABLE'     ,_FATAL),  	//checked
			array('lms2prod::*::*::*'                  ,'lms2delta' ,'COLUMN_DEFAULT'  ,_GOOD),  	//checked
			array('lms2prod::*::*::*'                  ,'lms2delta' ,'COLUMN_TYPE'     ,_FATAL),  	//checked
			
			array('lms2prod::*::*::date_stored'        ,'lms2delta' ,'MustExistPK'     ,_FATAL),   	// Script Passed - SQL FIX FAILED - 
																									// #### add other PKs to the sql
			array('lms2prod::*::*::*'      			   ,'lms2delta' ,'NoIndexButPK'    ,_FATAL),
			
			array('lms2delta::*::*'      			   ,'lms2delta' ,'NoDefault'       ,_WARNING),  // Script Passed - SQL FIX FAILED -
			array('lms2delta::*::*::date_stored'       ,'lms2delta' ,'NoDefault'       ,_GOOD),   	// Script Passed - SQL FIX FAILED -
			array('lms2delta::*::*::delta_type'        ,'lms2delta' ,'NoDefault'       ,_GOOD),   	// Script Passed - SQL FIX FAILED -
			array('lms2delta::*::*::delta_note'        ,'lms2delta' ,'NoDefault'       ,_GOOD),   	// Script Passed - SQL FIX FAILED -
			array('lms2delta::*::*::delta_rbac_user_id','lms2delta' ,'NoDefault'       ,_GOOD),   	// Script Passed - SQL FIX FAILED -
																									// #### some of the sqls are not correct
						
	// LMS2DELTA in LMS2PROD		
	//------------------------------------------------------
			array('lms2delta'                         ,'lms2prod'   ,'MissingTable'    ,_WARNING), 	//Script Passed SQL FIX Passed
			
			array('lms2delta::*::date_stored'         ,'lms2prod'   ,'MissingField'    ,_GOOD), 	//Script Passed SQL FIX Passed 
			array('lms2delta::*::delta_type'          ,'lms2prod'   ,'MissingField'    ,_GOOD), 	//Script Passed SQL FIX Passed   
			array('lms2delta::*::delta_note'          ,'lms2prod'   ,'MissingField'    ,_GOOD), 	//Script Passed SQL FIX Passed   
			array('lms2delta::*::delta_rbac_user_id'  ,'lms2prod'   ,'MissingField'    ,_GOOD), 	//Script Passed SQL FIX Passed   
			array('lms2delta::*::*'                   ,'lms2prod'   ,'MissingField'    ,_ERROR),	//Script Passed SQL FIX Passed 
			
			array('lms2delta::*::*::date_stored'      ,'lms2prod'   ,'MissingIndex'    ,_GOOD),		// Script Passed - SQL FIX Passed
			array('lms2delta::*::*::*'                ,'lms2prod'   ,'MissingIndex'    ,_WARNING),	// Script Passed - SQL FIX Passed
																									// #### SQL fix should be developed
			
			array('lms2delta::*::*'         		  ,'lms2delta'  ,'MustExistField'  ,_ERROR),   	// Script Passed - SQL FIX Passed
			array('lms2delta::*::date_stored'         ,'lms2delta'  ,'MustExistField'  ,_ERROR),   	// Script Passed - SQL FIX Passed
			array('lms2delta::*::delta_type'          ,'lms2delta'  ,'MustExistField'  ,_ERROR),   	// Script Passed - SQL FIX Passed
			array('lms2delta::*::delta_note'          ,'lms2delta'  ,'MustExistField'  ,_ERROR),   	// Script Passed - SQL FIX Passed
			array('lms2delta::*::delta_rbac_user_id'  ,'lms2delta'  ,'MustExistField'  ,_ERROR),   	// Script Passed - SQL FIX Passed
			
	// LMS2DELTA in LMS2DELTA		
	//------------------------------------------------------
			array('lms2delta::*::*::*'                  ,'lms2delta' ,'DATA_TYPE'       ,_FATAL),  	//checked
			array('lms2delta::*::*::*'                  ,'lms2delta' ,'COLLATION_NAME'  ,_FATAL),  	//checked
			array('lms2delta::*::*::*'                  ,'lms2delta' ,'IS_NULLABLE'     ,_FATAL),  	//checked
			array('lms2delta::*::*::*'                  ,'lms2delta' ,'COLUMN_DEFAULT'  ,_FATAL),  	//checked
			array('lms2delta::*::*::*'                  ,'lms2delta' ,'COLUMN_TYPE'     ,_FATAL),  	//checked
			array('lms2delta::*::*'                     ,'lms2delta' ,'MissingField'    ,_FATAL),	 
			array('lms2delta::*::*'                     ,'lms2delta' ,'MissingTable'    ,_FATAL),	 
			
/**
 * LMS2MIGRATION
 * ------------
 * Rules:
 * ---------------------------------------------------------------
 * 1. Checks the schema of same database in two different servers
 **/		
	// LMS2MIGRATION in LMS2MIGRATION		
	//------------------------------------------------------
			array('lms2migration::*::*::*'                  ,'lms2migration' ,'DATA_TYPE'       ,_FATAL),  	
			array('lms2migration::*::*::*'                  ,'lms2migration' ,'COLLATION_NAME'  ,_FATAL),  	
			array('lms2migration::*::*::*'                  ,'lms2migration' ,'IS_NULLABLE'     ,_FATAL),  	
			array('lms2migration::*::*::*'                  ,'lms2migration' ,'COLUMN_DEFAULT'  ,_FATAL),  	
			array('lms2migration::*::*::*'                  ,'lms2migration' ,'COLUMN_TYPE'     ,_FATAL),  	
			array('lms2migration::*::*'                     ,'lms2migration' ,'MissingField'    ,_FATAL),	 
			array('lms2migration::*::*'                     ,'lms2migration' ,'MissingTable'    ,_FATAL),	 
			
			
/**
 * DATAWHUREHOUE
 * ------------
 * Rules:
 * ---------------------------------------------------------------
 * 1. Checks the schema of same database in two different servers
 **/		
	// DATAWHUREHOUE in DATAWHUREHOUE		
	//------------------------------------------------------
			array('data_whurehouse::*::*::*'                  ,'data_whurehouse' ,'DATA_TYPE'       ,_FATAL),  	
			array('data_whurehouse::*::*::*'                  ,'data_whurehouse' ,'COLLATION_NAME'  ,_FATAL),  	
			array('data_whurehouse::*::*::*'                  ,'data_whurehouse' ,'IS_NULLABLE'     ,_FATAL),  	
			array('data_whurehouse::*::*::*'                  ,'data_whurehouse' ,'COLUMN_DEFAULT'  ,_FATAL),  	
			array('data_whurehouse::*::*::*'                  ,'data_whurehouse' ,'COLUMN_TYPE'     ,_FATAL),  	
			array('data_whurehouse::*::*'                     ,'data_whurehouse' ,'MissingField'    ,_FATAL),	 
			array('data_whurehouse::*::*'                     ,'data_whurehouse' ,'MissingTable'    ,_FATAL),	 
			
					
/**
 * LMS2VIEWS
 * ------------
 * Rules:
 * ---------------------------------------------------------------
 * 1. Checks the schema of same database in two different servers
 **/		
	// LMS2VIEWS in LMS2VIEWS		
	//------------------------------------------------------
			array('lms2views::*::*::*'                  ,'lms2views' ,'DATA_TYPE'       ,_FATAL),  	
			array('lms2views::*::*::*'                  ,'lms2views' ,'COLLATION_NAME'  ,_FATAL),  	
			array('lms2views::*::*::*'                  ,'lms2views' ,'IS_NULLABLE'     ,_FATAL),  	
			array('lms2views::*::*::*'                  ,'lms2views' ,'COLUMN_DEFAULT'  ,_FATAL),  	
			array('lms2views::*::*::*'                  ,'lms2views' ,'COLUMN_TYPE'     ,_FATAL),  	
			array('lms2views::*::*'                     ,'lms2views' ,'MissingField'    ,_FATAL),	 
			array('lms2views::*::*'                     ,'lms2views' ,'MissingTable'    ,_FATAL),				
						
/**
 * LMS2PROD
 * ------------
 * Rules:
 * ---------------------------------------------------------------
 * 1. Checks the schema of same database in two different servers
 **/		
	// LMS2PROD in LMS2PROD		
	//------------------------------------------------------
			array('lms2prod::*::*::*'                  ,'lms2prod' ,'DATA_TYPE'       ,_FATAL),  	
			array('lms2prod::*::*::*'                  ,'lms2prod' ,'COLLATION_NAME'  ,_FATAL),  	
			array('lms2prod::*::*::*'                  ,'lms2prod' ,'IS_NULLABLE'     ,_FATAL),  	
			array('lms2prod::*::*::*'                  ,'lms2prod' ,'COLUMN_DEFAULT'  ,_FATAL),  	
			array('lms2prod::*::*::*'                  ,'lms2prod' ,'COLUMN_TYPE'     ,_FATAL),  	
			array('lms2prod::*::*'                     ,'lms2prod' ,'MissingField'    ,_FATAL),	 
			array('lms2prod::*::*'                     ,'lms2prod' ,'MissingTable'    ,_FATAL),				

		    
		    
//************************************************************************************
/**
 * EONFLUX
 * ------------
 * Rules:
 * ---------------------------------------------------------------
 * 1. Checks the schema of same database in two different servers
 **/
// aeonflux VS aeonflux
//------------------------------------------------------
            array('aeonflux::*::*'                     ,'aeonflux' ,'MissingTable'    ,_FATAL),
            array('aeonflux::*::*'                     ,'aeonflux' ,'MissingField'    ,_FATAL),
		    array('aeonflux::*::*'                     ,'aeonflux' ,'MissingIndex'    ,_FATAL),
		    array('aeonflux::*::*'                     ,'aeonflux' ,'wrong-attributes',_FATAL),
		    
/**
 * COURSE_MERGE
 * ------------
 * Rules:
 * ---------------------------------------------------------------
 * 1. Checks the schema of same database in two different servers
 **/
// course_merge VS course_merge
//------------------------------------------------------
            array('course_merge::*::*'                     ,'course_merge' ,'MissingTable'    ,_FATAL),
            array('course_merge::*::*'                     ,'course_merge' ,'MissingField'    ,_FATAL),
            array('course_merge::*::*'                     ,'course_merge' ,'MissingIndex'    ,_FATAL),
            array('course_merge::*::*'                     ,'course_merge' ,'wrong-attributes',_FATAL),
 			array('course_merge::*::*::*'                  ,'course_merge' ,'DATA_TYPE'       ,_FATAL),
 			array('course_merge::*::*::*'                  ,'course_merge' ,'COLLATION_NAME'  ,_FATAL),
 			array('course_merge::*::*::*'                  ,'course_merge' ,'IS_NULLABLE'     ,_FATAL),
 			array('course_merge::*::*::*'                  ,'course_merge' ,'COLUMN_DEFAULT'  ,_FATAL),
 			array('course_merge::*::*::*'                  ,'course_merge' ,'COLUMN_TYPE'     ,_FATAL),
			
	);
		return $user_rulz;
	}