<?php
//then build the rulz array into an object array for better usage
class rules{
	static public $global_rules    = array();
	protected	  $source_database = null,
				  $source_table    = null,
				  $source_field    = null,
				  $source_index    = null,
				  
				  $target_database = null,
				  $target_table    = null,
				  $target_field    = null,
				  
				  $error_type      = null,
				  $level_overide   = NULL
					;
					
	public function __construct(array $rulz){
		$source                = explode('::',$rulz[0]);
		$this->source_database = $source[0];
		$this->source_table    = isset($source[1])?$source[1]:'*';
		$this->source_field    = isset($source[2])?$source[2]:'*';
		$this->source_index    = isset($source[3])?$source[3]:'*';
		
		$target                = explode('::',$rulz[1]);
		$this->target_database = $target[0];
		$this->target_table    = isset($target[1])?$target[1]:'*';
		$this->target_field    = isset($target[2])?$target[2]:'*';
		
		$this->error_type      = $rulz[2];
		$this->level_overide   = $rulz[3];
		
	}

	public function getMustExistFields(array $error){
		
		if($error['error_type'] == $this->error_type && 
		   $this->source_database == $error['source_db']    &&
		   $this->source_table 	  == $error['source_table'] &&
		   $this->target_database == $error['target_db']    &&
		   $this->target_table 	  == $error['target_table'])
		   {
				return true;
			}
	}
	
	public function doesRuleApply(array $error){
		
		if($error['error_type'] == $this->error_type && 
		   $this->source_database == $error['source_db']    &&
		   $this->source_table 	  == $error['source_table'] &&
		   $this->source_field 	  == $error['source_field'] &&
		   $this->source_index 	  == $error['source_index'] &&
		   $this->target_database == $error['target_db']    &&
		   $this->target_table 	  == $error['target_table'] &&
		   $this->target_field 	  == $error['target_field']
		){
			return true;
		}
		return false;
	}
	
	public function getErrorLvl(){
		return $this->level_overide;
	}
	
	public function getSource_field(){
		return $this->source_field;
	}
	
	public function getSource_index(){
		return $this->source_index;
	}
	
	public function getConfigValues($error, $key_type){
		$output = array();
		if($error['error_type'] == $this->error_type        && 
		   $error['source_db']  == $this->source_database   &&
		   $error['target_db']  == $this->target_database    
		){
			return array($this->{$key_type}=>$this->level_overide);
		}
		return false;
	}
	
	
	
	
	
	
}