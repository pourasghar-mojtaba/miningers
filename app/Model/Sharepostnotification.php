<?php

class Sharepostnotification extends AppModel {
	public $name = 'Sharepostnotification';
	public $useTable = "sharepost_notifications"; 
	public $primaryKey = 'id';
	
	  var $actsAs = array('Containable');
   
	
	public $belongsTo = array(
		'Allpost' => array(
			'className' => 'Allpost',
			'foreignKey' => 'sharepost_id',
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'from_user_id',
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