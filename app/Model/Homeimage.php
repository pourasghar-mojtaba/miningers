<?php

class Homeimage extends AppModel {
	public $name = 'Homeimage';
	public $useTable = "home_images"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	
	
}

?>