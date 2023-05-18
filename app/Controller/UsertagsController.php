<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class UsertagsController extends AppController {

   var $name = 'Usertags';	 
 /**
* follow to anoher user
* @param undefined $id
* 
*/
public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('get_user_tag');  
}


function admin_index()
	{
		$this->Usertag->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
				
		if(isset($this->request->data['Usertag']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Usertag.title LIKE' => ''.$this->request->data['Usertag']['search'].'%' ,'Usertag.id not in'=>array(1,2)),
				'limit' => $limit,
				'order' => array(
					'Usertag.id' => 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
			    'conditions' => array('Usertag.id not in (1,2)'),
				'limit' => $limit,
				'order' => array(
					'Usertag.id' => 'desc'
				)
			);
		}		
		$usertags = $this->paginate('Usertag');
		$this->set(compact('usertags'));
	}


	function admin_add()
	{
		$this->Usertag->recursive = -1;
		if($this->request->is('post'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			
			$result = $this->Usertag->find('count', array('conditions' => array('Usertag.title' =>$this->request->data['Usertag']['title'])));
			 if($result > 0){
			 	$this->Session->setFlash(__('the_usertag_exist'), 'admin_error');
				$this->redirect(array('action' => 'index'));
			 }
			$this->Usertag->create();
			if($this->Usertag->save($this->request->data))
			{
				$this->Session->setFlash(__('the_usertag_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_usertag_could_not_be_saved'));
			}
		}
		 
		
	}

/**
* 
* @param undefined $id
* 
*/
	function _set_usertag($id)
	{
		$this->Usertag->recursive = -1;
		$this->Usertag->id = $id;
		if(!$this->Usertag->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_usertag'));
			return;
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this->Usertag->findById($id);
	    }
	    
	    $this->set('usertag', $this->request->data);
	    
	    return $this->request->data;
	}


	function admin_edit($id = null)
	{
		$this->Usertag->id = $id;
		if(!$this->Usertag->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_usertag'));
			return;
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			if($this->Usertag->save($this->request->data))
			{
				$this->Session->setFlash(__('the_usertag_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_usertag_could_not_be_saved'));
			}
		}
		
		$this->_set_usertag($id);
		
	}

	function admin_delete($id = null)
	{
		$this->Usertag->id = $id;
		if(!$this->Usertag->exists())
		{
			//$this->Session->setFlash(__('invalid_id_for_usertag'));
			$this->Session->setFlash(__('invalid_id_for_usertag'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		$this->Usertag->Userrelatetag->recursive = -1;
		$result = $this->Usertag->Userrelatetag->find('count', array('conditions' => array('Userrelatetag.usertag_id' =>$id)));
			 if($result > 0){
			 	$this->Session->setFlash(__('the_usertag_uses_from_users'), 'admin_error');
				$this->redirect(array('action' => 'index'));
			 }
		
		if($this->Usertag->delete($id))
		{
			$this->Session->setFlash(__('delete_usertag_success'), 'admin_success');
			$this->redirect(array('action' => 'index'));
		}
		else
		{
			$this->Session->setFlash(__('delete_usertag_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
		
	}

 /**
 * search on tag
 * 
*/
 function search(){
	$this->Usertag->recursive = -1;
	$search_word = $_REQUEST['search_word'];
	$options['fields'] = array(
				'Usertag.id',
				'Usertag.title'
			   );
		$options['joins'] = array(
				array('table' => 'userrelatetags',
					'alias' => 'Userrelatetag',
					'type' => 'LEFT',
					'conditions' => array(
					'Usertag.id = `Userrelatetag`.usertag_id ',
					)
				) ,
				
				array('table' => 'users',
					'alias' => 'User',
					'type' => 'LEFT',
					'conditions' => array(
					'`Userrelatetag`.user_id = `User`.id ',
					)
				)
				
			);	
				   
		$options['conditions'] = array(
			   'Usertag.title LIKE'=> "$search_word%" ,
		);
		
		$options['order'] = array(
				'Usertag.id'=>'desc'
			);
		$options['limit'] = 3;
		
		$users = $this->Usertag->find('all',$options);
		$this->set(compact('users'));
}
 
 
 /**
 * 
 * 
*/
  function search_suggest(){
	$this->Usertag->recursive = -1;
	$search_word = $_REQUEST['search_word'];
	$options['fields'] = array(
				'Usertag.id',
				'Usertag.title'
			   );
				   
		$options['conditions'] = array(
			   'Usertag.title LIKE'=> "$search_word%" ,
		);
		
		$options['order'] = array(
				'Usertag.id'=>'desc'
			);
		$options['limit'] = 3;
		
		$usertags = $this->Usertag->find('all',$options);
		$this->set(compact('usertags'));
	
		$this->render('/Elements/Users/Ajax/search_suggest','ajax');
}
/**
* 
* 
*/
 function tag_search()
 {
 	$User_Info= $this->Session->read('User_Info');
	$search_word =  $_GET["term"] ;
	/*$options['fields'] = array(
				'DISTINCT(Usertag.id)',
				'Usertag.title',
				'COUNT(Usertag.id) as tag_count'
			   );
	
    $options['joins'] = array(
				array('table' => 'userrelatetags',
					'alias' => 'Userrelatetag',
					'type' => 'INNER',  
					'conditions' => array(
					'Userrelatetag.usertag_id = Usertag.id ',
					)
				) 
				
			);	
				   
	$options['conditions'] = array(
		array("OR" => array(
				"Usertag.title LIKE" => "$search_word%" ,
				"Usertag.title LIKE " => "% $search_word%"
		  )),
		'Userrelatetag.user_id <> ' =>$User_Info['id'] 
	);*/
	
	$options['fields'] = array(
				'DISTINCT(Usertag.id)',
				'Usertag.title',
				'(select count(*) from userrelatetags where usertag_id = Usertag.id and user_id <> '.$User_Info['id'] .') as tag_count'
			   );
	$options['conditions'] = array(
		array("OR" => array(
				"Usertag.title LIKE" => "$search_word%" ,
				"Usertag.title LIKE " => "% $search_word%"
		  ))
	);		   
	
	$options['group'] = 'Usertag.id';
	$options['order'] = array(
			'Usertag.id'=>'desc'
		);
	$options['limit'] = 5;
	
	$usertags = $this->Usertag->find('all',$options);
    $this->set('search_result',$usertags);
    $this->render('/Elements/Usertags/Ajax/tag_search','ajax'); 											 
 }
 
 
 /**
 * 
 */
 
 function get_user_tag($id=null)
 {
 	$this->Usertag->recursive = -1;
		$options['fields'] = array(
				'Usertag.id',
				'Usertag.title'
			   );
		$options['joins'] = array(
				array('table' => 'userrelatetags',
					'alias' => 'Userrelatetag',
					'type' => 'LEFT',
					'conditions' => array(
					'Userrelatetag.usertag_id = Usertag.id ',
					)
				) 
				
			);	
				   
		$options['conditions'] = array(
			"Userrelatetag.user_id"=>$id 
		);
		
		$options['order'] = array(
		'Usertag.id'=>'asc'
		);
		
		$tags = $this->Usertag->find('all',$options);
		//$this->set(compact('tags'));
		return $tags;
 }
 
 
 /**
 * 
 * 
*/
 function last_tag_list(){
 	 $this->Usertag->recursive = -1;
	 $tags = $this->Usertag->query(
	 	"
			select * from (
				select 
					distinct(Usertag.id) ,
					Usertag.title  ,
					(select count(*) from  userrelatetags where usertag_id=Usertag.id) as count
					
			    from usertags as Usertag
				
				order by Usertag.id desc limit 0 , 15
			) as Usertag 
		"
	 );
	
		return $tags; 
 }
 
 
 
 
}
