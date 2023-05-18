<?php

class Aclrole extends AppModel {
	public $name = 'Aclrole';
	public $useTable = "aclroles"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	
		
	
}

?>