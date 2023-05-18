<?php

class Errorlog extends AppModel {
	public $name = 'Errorlog';
	public $useTable = "error_logs"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
/**
* 
* @param undefined $controller
* @param undefined $body
* 
*/
 function get_log($controller,$body)
 {
    $this->set(array(
		'controller'=>$controller ,
		'body' => $body
	)); 
	 
	if($this->save())
	{
		return TRUE;
	}
	return FALSE;
	 	
 }

	
}

?>