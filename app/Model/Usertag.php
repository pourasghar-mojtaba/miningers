<?php

class Usertag extends AppModel {
	public $name = 'Usertag';
	public $useTable = "usertags"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   	
	public $hasMany = array(
		'Userrelatetag' => array(
			'className' => 'Userrelatetag',
			'foreignKey' => 'usertag_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);	
		
	
}

?>