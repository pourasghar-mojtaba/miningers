<?php

class Activateemail extends AppModel {
	public $name = 'Activateemail';
	public $useTable = "activate_emails"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
   public $hasOne = array(
		'Sendemail' => array(
		'className' => 'Sendemail',
		'foreignKey' => 'user_id',
		'dependent' => false
		)
	);
	
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
		)
	);
	
}

?>