<?php

class Channel extends AppModel {
	public $name = 'Channel';
	public $useTable = "channels"; 
	public $primaryKey = 'id';
	
	  var $actsAs = array('Containable');
   
	
	public $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'channel_id',
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
		'Blogchannel' => array(
			'className' => 'Blogchannel',
			'foreignKey' => 'channel_id',
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