<?php

class Feed extends ReaderAppModel {
	public $name = 'Feed';
	public $useTable = "userfeeds"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	
   public $belongsTo = array(
		'User' => array(
			'className'  => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)		
	);
	
   public $hasMany = array(
		'Feedurl' => array(
			'className' => 'Feedurl',
			'foreignKey' => 'user_feed_id',
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
        'Feedpost' => array(
			'className' => 'Feedpost',
			'foreignKey' => 'user_feed_id',
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
        'Feedtime' => array(
			'className' => 'Feedtime',
			'foreignKey' => 'user_feed_id',
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