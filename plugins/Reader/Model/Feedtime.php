<?php

class Feedtime extends ReaderAppModel {
	public $name = 'Productimage';
	public $useTable = "feedtimes"; 
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