<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class Role extends AppModel
{
    public $name = 'Role';
	public $useTable = "roles"; 
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'role_id',
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
