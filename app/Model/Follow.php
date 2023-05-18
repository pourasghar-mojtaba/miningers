<?php

class Follow extends AppModel {
	public $name = 'Follow';
	public $useTable = "follows"; 
	public $primaryKey = 'id';
	
	  var $actsAs = array('Containable');
   
	
	public $belongsTo = array(
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
		),
		'BlockUser' => array(
			'className' => 'BlockUser',
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
		),
		'Sharepost' => array(
			'className' => 'Sharepost',
			'foreignKey' => 'to_user_id',
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


  public $hasMany = array(
		'Follownotification' => array(
			'className' => 'Follownotification',
			'foreignKey' => 'follow_id',
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