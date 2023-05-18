<?php

class Userrelatetag extends AppModel {
	public $name = 'Userrelatetag';
	public $useTable = "userrelatetags"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Usertag' => array(
			'className' => 'Usertag',
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