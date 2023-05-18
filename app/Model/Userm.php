<?php

App::uses('CakeEmail', 'Network/Email');

class Userm extends AppModel {

    public $name = 'Userm';
	public $useTable = "userms"; 
	public $primaryKey = 'id';
	
    var $actsAs = array( 'Containable');
  
   
}