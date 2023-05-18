<?php

class Libraryrelateauthor extends AppModel {
	public $name = 'Libraryrelateauthor';
	public $useTable = "libraryrelateauthors"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   

   public $belongsTo = array(
		'Libraryauthor' => array(
			'className' => 'Libraryauthor',
			'foreignKey' => 'library_author_id',
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
        'Library' => array(
			'className' => 'Library',
			'foreignKey' => 'library_id',
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