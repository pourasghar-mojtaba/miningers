<?php

class Productreview extends ShopAppModel {
	public $name = 'Productreview';
	public $useTable = "productreviews"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   

   public $belongsTo = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
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
	
	public $hasMany = array(
		'Productreviewpdf' => array(
			'className' => 'Productreviewpdf',
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