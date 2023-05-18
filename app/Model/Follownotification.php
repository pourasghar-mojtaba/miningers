<?php

class Follownotification extends AppModel {
	public $name = 'Follownotification';
	public $useTable = "follow_notifications"; 
	public $primaryKey = 'id';
	
	  var $actsAs = array('Containable');
   
	
	public $belongsTo = array(
		'Follow' => array(
			'className' => 'Follow',
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