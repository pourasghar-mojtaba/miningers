<?php

class Postnotification extends AppModel {
	public $name = 'Postnotification';
	public $useTable = "post_notifications"; 
	public $primaryKey = 'id';
	
	  var $actsAs = array('Containable');
   
	
	public $belongsTo = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'post_id',
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