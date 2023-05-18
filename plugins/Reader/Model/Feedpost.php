<?php

class Feedpost extends ReaderAppModel {
	public $name = 'Feedpost';
	public $useTable = "feedposts"; 
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