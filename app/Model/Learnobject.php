<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class Learnobject extends AppModel
{
    public $name = 'Learnobject';
	public $useTable = "learn_objects"; 
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
 */
	public $hasMany = array(
		'Learnobjectuser' => array(
			'className' => 'Learnobjectuser',
			'foreignKey' => 'learn_object_id',
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
