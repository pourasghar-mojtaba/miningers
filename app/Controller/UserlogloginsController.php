<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
/**
 * Users Controller
 *
 * @property User $User
 */
class UserlogloginsController extends AppController {

    var $name = 'Userloglogins';
	var $helpers = array('Gilace','Excel');
	var $components = array('Gilace','Httpupload','GilaceDate'); 


var $paginate = array('Userloglogin'=>array(
	    'limit' => 1,
	    'order' => array(
	         'Userloglogin.id' => 'asc'
	     )
	),
	
);


function admin_index()
	{
		$this->Userloglogin->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 50;
				
		if(isset($this->request->data['Userloglogin']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
				'fields'=>array(
					'Userloglogin.id',
                    'Userloglogin.modified',
                    'Userloglogin.created',
					'User.name',
					'User.user_name',
					'User.email'					 
				),
				'joins'=>array(array('table' => 'users',
					'alias' => 'User',
					'type' => 'INNER',
					'conditions' => array(
					'User.id = `Userloglogin`.user_id ',
					)
				 )),
'conditions' => array('Userloglogin.name LIKE' => ''.$this->request->data['Userloglogin']['search'].'%' ,'User.role_id <>'=>1),
				'limit' => $limit,
				'order' => array(
					'Userloglogin.id' => 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array('Userloglogin'=>array(
			    'fields'=>array(
					'sum(Userloglogin.count_login) as sum_login',
                    'date(Userloglogin.created) as created'					 
				) ,
				//'conditions' => array('date(Userloglogin.created)'=> '".date('Y-m-d')."'),
				'limit' => $limit,
                'group' => 'date(Userloglogin.created)' ,
				'order' => array(
					'Userloglogin.created' => 'desc'
				)
			));
		}		
		$userloglogins = $this->paginate('Userloglogin');
		$this->set(compact('userloglogins'));
	}

/**
* 
* @param undefined $date
* 
*/
function admin_onedate_export($date)
{
	$this->Userloglogin->recursive = -1;
	$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.email',
				'User.user_name',
                'Userloglogin.count_login',
                'Userloglogin.modified',
                'Userloglogin.created'
				
			   );
	$options['joins'] = array(
				array('table' => 'users',
					'alias' => 'User',
					'type' => 'INNER',
					'conditions' => array(
					'User.id = `Userloglogin`.user_id ',
					)
				)
				
			);	
    $options['conditions'] = array(
			'date(Userloglogin.created)' => $date
		);        
					   
	$userloglogins = $this->Userloglogin->find('all',$options); 
	$this->set(compact('userloglogins'));
    $this->set('date',$date);
}
/**
* 
* @param undefined $from_date
* @param undefined $to_date
* 
*/
function admin_alldate_export($from_date='',$to_date='')
{
	$this->Userloglogin->recursive = -1;
	$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.email',
				'User.user_name',
                'Userloglogin.count_login',
                'Userloglogin.modified',
                'Userloglogin.created'
				
			   );
	$options['joins'] = array(
				array('table' => 'users',
					'alias' => 'User',
					'type' => 'INNER',
					'conditions' => array(
					'User.id = `Userloglogin`.user_id ',
					)
				)
				
			);
    if($from_date!='' && $to_date!=''){
      $options['conditions'] =array(
      'date(Userloglogin.created) <=' => $to_date, 'date(Userloglogin.created) >=' => $from_date
      ) ; 
    }  
    $options['order'] =array('Userloglogin.created'=>'desc');     	
           
					   
	$userloglogins = $this->Userloglogin->find('all',$options); 
	$this->set(compact('userloglogins'));
    $this->set('from_date',$from_date);
    $this->set('to_date',$to_date);
}




		
}

