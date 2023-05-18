<?php

class Feedurl extends ReaderAppModel {
	public $name = 'Feedurl';
	public $useTable = "feedurls"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   

   public $belongsTo = array(
		'Feed' => array(
			'className' => 'Feed',
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