<?php

class Productcategory extends ShopAppModel {
	public $name = 'Productcategory';
	public $useTable = "productcategories"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
   public $hasMany = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'id',
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