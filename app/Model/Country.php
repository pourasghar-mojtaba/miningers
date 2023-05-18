<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class Country extends AppModel
{
    public $name = 'Country';
	public $useTable = "countries"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

 /* hasMany associations
 *
 * @var array
 *//*
	public $hasMany = array(
		'Court' => array(
			'className' => 'Court',
			'foreignKey' => 'country_id',
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
	);*/

	
	 
}
