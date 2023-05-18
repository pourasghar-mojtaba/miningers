<?php

class Productreviewpdf extends ShopAppModel {
	public $name = 'Productreviewpdf';
	public $useTable = "productreviewpdfs"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   

   public $belongsTo = array(
		'Productreview' => array(
			'className' => 'Productreview',
			'foreignKey' => 'productreview_id',
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