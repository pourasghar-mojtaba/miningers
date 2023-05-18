<?php

class Page extends AppModel {
	public $name = 'Page';
	public $useTable = "pages"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	
 
	
}

?>