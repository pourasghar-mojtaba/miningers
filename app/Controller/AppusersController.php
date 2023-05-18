<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email'); 
App::uses('Sanitize', 'Utility');
/**
 * Users Controller
 *
 * @property User $User
 */
class AppusersController extends AppController {

    var $name = 'Appusers';
	var $helpers = array('Gilace','PersianDate');


/**
* 
* @param undefined $user_id
* 
*/
function _get_edit_info($user_id){
	$this->Appuser->User->recursive = -1;
	$options['fields'] = array(
		'User.id',
		'User.name',
		'User.sex',
		'User.user_name',
		'User.user_type',
		'User.search_with_email',
		'User.email',
		'User.cover_image',
		'User.image'
   );
	$options['conditions'] = array(
	'User.id '=> $user_id
	);	   
	$user= $this->Appuser->User->find('first',$options);
	return $user;
}

function active_app (){
	
	$User_Info= $this->Session->read('User_Info');
	
	if($User_Info['country_id']!=104){
		throw new NotFoundException(__('invalid page'));
		return;
	}
	
	
	$this->Appuser->recursive = -1;
		
	$this->set('user',$this->_get_edit_info($User_Info['id']));
	
	if($this->request->is('post') || $this->request->is('put'))
	{
		$this->request->data = array();
		
		$info = $this->Appuser->find('first', array('conditions' => array('Appuser.user_id' => $User_Info['id'])));
		
		if(!empty($info)){
			$id=$info['Appuser']['id'];
			
			$long = strtotime(date('Y-m-d H:i:s'));
			$random= rand(1000,1000000); 
			
			$this->Session->write('Pay_Info',array(
				'title' => __('active_infomadan_app') ,
				'sum_price' => 600000, 
				'other_price' => 0 ,
				'sum_item' => 1,
				'orderid' => $long,
				'model'=> 'Appuser' ,
				'token'=>$random,
				'row_id'=>$id
	            
			));
			$this->redirect('/getway/banks/pay/'.$random.'?cn=appusers&ac=active_app');
		}
		else{
			$this->request->data['Appuser']['user_id']= $User_Info['id'];
			$this->request->data['Appuser']['bank_id']= 0;
			$this->request->data['Appuser']['price']= 600000;
			$this->request->data['Appuser']['bankmessage_id']= -1;
			$this->request->data['Appuser']['refid']= 0;
			$this->request->data['Appuser']['sale_reference_id']= 0;
			$this->request->data['Appuser']['sale_order_id']= 0;
			$this->request->data['Appuser']['sum_rcvd_amnt']= 0;
			$this->Appuser->create();
			if($this->Appuser->save($this->request->data)){
				$id=$this->Appuser->getLastInsertID();
				$long = strtotime(date('Y-m-d H:i:s'));
				$random= rand(1000,1000000); 
				
				$this->Session->write('Pay_Info',array(
					'title' => __('active_infomadan_app') ,
					'sum_price' => 600000, 
					'other_price' => 0 ,
					'sum_item' => 1,
					'orderid' => $long,
					'model'=> 'Appuser' ,
					'token'=>$random,
					'row_id'=>$id
		            
				));
				$this->redirect('/getway/banks/pay/'.$random.'?cn=appusers&ac=active_app');
			}
		}
		
		
		
	}
	
	$options = array();
			$this->loadModel('User');
			$this->User->recursive = -1;
			$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.user_name',
				'User.cover_image',
				'User.cover_x',
				'User.cover_y',
				'User.cover_zoom',
				'User.image'
			   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
			$user= $this->User->find('first',$options);
			$this->set(compact('user'));
	
	$this->set('title_for_layout',__('active_infomadan_app'));
    $this->set('description_for_layout',__('active_infomadan_app'));
    $this->set('keywords_for_layout',__('active_infomadan_app'));
	$user = $this->Appuser->find('first', array('conditions' => array('Appuser.user_id' => $User_Info['id'])));
	$this->set('appuser',$user);
}
 
 
 function admin_index()
	{
		$this->Appuser->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 50;
				
		if(isset($this->request->data['User']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
				'fields'=>array(
					'Appuser.id',
					'User.name',
					'User.user_name',
					'Appuser.price',
					'Appuser.sum_rcvd_amnt',
					'Appuser.refid',
					'Appuser.sale_reference_id',
					'Appuser.sale_order_id',
					'Bank.bank_name',
					'Bankmessage.message',
					'Appuser.created'
                    
				),
				'joins'=>array(
                    array('table' => 'users',
    					'alias' => 'User',
    					'type' => 'INNER',
    					'conditions' => array(
    					'User.id = Appuser.user_id ',
    					)
    				 ),
                    array('table' => 'banks',
    					'alias' => 'Bank',
    					'type' => 'LEFT',
    					'conditions' => array(
    					'Bank.id = Appuser.bank_id ',
    					)
    				 ),
                    array('table' => 'bankmessags',
    					'alias' => 'Bankmessage',
    					'type' => 'LEFT',
    					'conditions' => array(
    					'Bankmessage.id = Appuser.bankmessage_id ',
    					)
    				 )  
                 
                 ),
'conditions' => array('User.name LIKE' => ''.$this->request->data['User']['search'].'%' ,'User.role_id <>'=>1),
				'limit' => $limit,
				'order' => array(
					'User.id' => 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
			    'fields'=>array(
					'Appuser.id',
					'User.name',
					'User.user_name',
					'Appuser.price',
					'Appuser.sum_rcvd_amnt',
					'Appuser.refid',
					'Appuser.sale_reference_id',
					'Appuser.sale_order_id',
					'Bank.bank_name',
					'Bankmessage.message',
                    'Appuser.created'
                    
				),
				'joins'=>array(
                    array('table' => 'users',
    					'alias' => 'User',
    					'type' => 'INNER',
    					'conditions' => array(
    					'User.id = Appuser.user_id ',
    					)
    				 ),
                    array('table' => 'banks',
    					'alias' => 'Bank',
    					'type' => 'LEFT',
    					'conditions' => array(
    					'Bank.id = Appuser.bank_id ',
    					)
    				 ),
                    array('table' => 'bankmessags',
    					'alias' => 'Bankmessage',
    					'type' => 'LEFT',
    					'conditions' => array(
    					'Bankmessage.id = Appuser.bankmessage_id ',
    					)
    				 )  
                 
                 ),
				'conditions' => array('User.role_id <> 1 '),
				'limit' => $limit,
				'order' => array(
					'User.id' => 'desc'
				)
			);
		}		
		$users = $this->paginate('Appuser');
		$this->set(compact('users'));
	}
	
function admin_user_export()
{
	$this->User->recursive = -1;
	$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.created',
				'User.email',
				'User.industry_id',
				'User.user_name',
				'User.user_type',
				'User.role_id',
				'User.location',
				'User.site',
				'User.status',
				'User.register_key',
				'Industry.id',
				'Industry.title_'.$this->Session->read('Config.language').' as title',
				'Role.name'
				
			   );
	$options['joins'] = array(
				array('table' => 'industries',
					'alias' => 'Industry',
					'type' => 'LEFT',
					'conditions' => array(
					'Industry.id = User.industry_id ',
					)
				) ,
				array('table' => 'roles',
					'alias' => 'Role',
					'type' => 'LEFT',
					'conditions' => array(
					'Role.id = User.role_id ',
					)
				) 
				
			);	
					   
	$users = $this->User->find('all',$options); 
	$this->set(compact('users'));
}	


		
}

