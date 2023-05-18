<?php

class Newslettersend extends AppModel {
	public $name = 'Newslettersend';
	public $useTable = "newsletter_sends"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
    
	 public $hasOne = array(
		'Sendemail' => array(
			'className' => 'Sendemail',
			'foreignKey' => 'user_id',
			'dependent' => false
		)
	);
	
    public $belongsTo = array(
		'Newsletter' => array(
			'className' => 'Newsletter',
			'foreignKey' => 'newsletter_id',
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