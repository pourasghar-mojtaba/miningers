<?php

class Blogtag extends AppModel {
	public $name = 'Blogtag';
	public $useTable = "blogtags"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   	
	public $hasMany = array(
		'Blogrelatetag' => array(
			'className' => 'Blogrelatetag',
			'foreignKey' => 'blogtag_id',
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