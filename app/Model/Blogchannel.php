<?php

class Blogchannel extends AppModel {
	public $name = 'Blogchannel';
	public $useTable = "blog_channels"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	public $belongsTo = array(
		'Blog' => array(
			'className' => 'Blog',
			'foreignKey' => 'blog_id',
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
		'Channel' => array(
			'className' => 'Channel',
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