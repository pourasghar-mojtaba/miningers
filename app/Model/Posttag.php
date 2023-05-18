<?php

class Posttag extends AppModel {
	public $name = 'Posttag';
	public $useTable = "posttags"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   	
	public $hasMany = array(
		'Postrelatetag' => array(
			'className' => 'Postrelatetag',
			'foreignKey' => 'posttag_id',
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