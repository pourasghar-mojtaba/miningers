<?php

class Recipe extends AppModel {
	public $name = 'Recipe';
	public $useTable = "recipes"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   


 
	
}

?>