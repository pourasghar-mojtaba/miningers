<?php

class Newslettertemplate extends AppModel {
	public $name = 'Newslettertemplate';
	public $useTable = "newsletter_templates"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	public $hasMany = array(
		'Newsletter' => array(
			'className' => 'Newsletter',
			'foreignKey' => 'newsletter_template_id',
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