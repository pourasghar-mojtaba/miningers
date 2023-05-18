<?php

class Aclitem extends AppModel {
	public $name = 'Aclitem';
	public $useTable = "aclitems"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	
		
	
}

?>