<?php

class Library extends AppModel {
	public $name = 'Library';
	public $useTable = "libraries"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	

   public $hasMany = array(
        'Libraryrelatetag' => array(
			'className' => 'Libraryrelatetag',
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
		),
        'Librarypdf' => array(
			'className' => 'Librarypdf',
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
		),
        'Libraryrelateauthor' => array(
			'className' => 'Libraryrelateauthor',
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