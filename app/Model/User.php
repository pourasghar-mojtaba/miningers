<?php

App::uses('CakeEmail', 'Network/Email');

class User extends AppModel {

    public $name = 'User';

    var $actsAs = array( 'Containable');
  
    
   public $hasOne = array(
		'Sendemail' => array(
			'className' => 'Sendemail',
			'foreignKey' => 'user_id',
			'dependent' => false
		),
		'Appuser' => array(
			'className'  => 'Appuser',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		) 
	);
    
	
  public $belongsTo = array(
		'Role' => array(
			'className'  => 'Role',
			'foreignKey' => 'role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Industry' => array(
			'className'  => 'Industry',
			'foreignKey' => 'industry_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
		
	);
	
  public $hasMany = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'user_id',
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
		'Blog' => array(
			'className' => 'Blog',
			'foreignKey' => 'user_id',
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
		'Follow' => array(
			'className' => 'Follow',
			'foreignKey' => 'from_user_id',
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
		'Blockuser' => array(
			'className' => 'Blockuser',
			'foreignKey' => 'from_user_id',
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
		'Chat' => array(
			'className' => 'Chat',
			'foreignKey' => 'from_user_id',
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
		'Postnotification' => array(
			'className' => 'Postnotification',
			'foreignKey' => 'from_user_id',
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
		'LikeunlikeNotification' => array(
			'className' => 'LikeunlikeNotification',
			'foreignKey' => 'from_user_id',
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
		'Sharepostnotification' => array(
			'className' => 'Sharepostnotification',
			'foreignKey' => 'from_user_id',
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
		'Infractionreport' => array(
			'className' => 'Infractionreport',
			'foreignKey' => 'from_user_id',
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
		'Activateemail' => array(
			'className' => 'Activateemail',
			'foreignKey' => 'user_id',
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
        'Learnobjectuser' => array(
			'className' => 'Learnobjectuser',
			'foreignKey' => 'user_id',
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
		'Userloglogin' => array(
			'className' => 'Userloglogin',
			'foreignKey' => 'user_id',
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
		'Userrelatetag' => array(
			'className' => 'Userrelatetag',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)/*,
		'Feed' => array(
			'className' => 'Feed',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		) */
	);	
	
	
	
	function parentNode()
	{
        if (!$this->id && empty($this->data))
        {
            return null;
        }
        
        $data = $this->data;
        
        if (empty($this->data))
        {
            $data = $this->read();
        }
        elseif(isset($this->id) && empty($data[$this->alias]['role_id']))
        {
            /*
             * The role_id field was not intended to be saved,
             * but we need it in order to find the parent node in the Aros
             */
            $data[$this->alias]['role_id'] = $this->field('role_id', array('User.id' => $this->id));
        }
        
        if (empty($data[$this->alias]['role_id']))
        {
            return null;
        }
        else
        {
            return array('Role' => array('id' => $data[$this->alias]['role_id']));
        }
    }
	
  
  		
	
   public function beforeSave($options = NULL) {
       if (isset($this->data[$this->alias]['password'])) {
           if($this->data[$this->alias]['password']!='')
		   		$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
			else
			$this->data[$this->alias]['password'] =$this->data[$this->alias]['old_password'];	
       }
       return true;
   }
   
}