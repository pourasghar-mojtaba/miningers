<?php

class Newsletter extends AppModel {
	public $name = 'Newsletter';
	public $useTable = "newsletters"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	public $hasMany = array(
		'Newslettersend' => array(
			'className' => 'Newslettersend',
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
	
	public $belongsTo = array(
		'Newslettertemplate' => array(
			'className' => 'Newslettertemplate',
			'foreignKey' => 'newsletter_template_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
		
	);
 
	
}

?>