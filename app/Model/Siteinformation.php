<?php

class Siteinformation extends AppModel {
   
	public $name = 'Siteinformation';
	public $useTable = "settings"; 

/**
* 

* 
*/
 function get_setting()
 {
   return $this->find('first');	 	
 }
	
}

?>