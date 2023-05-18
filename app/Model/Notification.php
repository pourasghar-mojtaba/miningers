<?php

class Notification extends AppModel {
	public $name = 'Notification';
	public $useTable = "notifications"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   

 
	
}

?>