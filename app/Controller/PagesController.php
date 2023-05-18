<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('view','get_child_pages');
}

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display() {
		
		//pr($this->params); exit;
		
		Controller::loadModel('Siteview');
		$this->Siteview->get_log();
		
		$this->Session->write('Site_Info',array(
		'OldController' => $this->request->params['controller'] ,
		'OldAction' => $this->request->params['action'] 
		));
		
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;
		
		

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		//$this->set(compact('page', 'subpage', 'title_for_layout'));
		
		$this->set('title_for_layout',__('site_title'));
		Controller::loadModel('Siteinformation');
    	$setting=$this->Siteinformation->get_setting();
		$this->set('description_for_layout',$setting['Siteinformation']['site_description']);
		$this->set('keywords_for_layout',$setting['Siteinformation']['site_keywords']);
		 
		if(!$this->Session->check('User_Info'))
		{			 
			$this->render('/Elements/Users/login');
		}
		else $this->render(implode('/', $path));
	}
	
	/**
	* 
	* @param int $id
	* 
*/
	public function view($id){
		
		$this->Page->id = $id;
		if(!$this->Page->exists())
		{
			throw new NotFoundException(__('invalid id for page'));
		}
	    $this->Page->findById($id);
		
		$options['fields'] = array(
				'Page.id',
				'Page.title_'.$this->Session->read('Config.language').' as title',
				'Page.body_'.$this->Session->read('Config.language').' as body',
				'Page.meta_'.$this->Session->read('Config.language').' as meta' ,
				'Page.keyword_'.$this->Session->read('Config.language').' as keyword'
			   );
			   
		$options['conditions'] = array(
				'Page.id' => $id
			);
	    $page = $this->Page->find('first',$options); 
		$this->set(compact('page'));
		
		$this->set('title_for_layout',$page['Page']['title']);
		$this->set('description_for_layout',str_replace('#',',',$page['Page']['meta']));
		$this->set('keywords_for_layout',str_replace('#',',',$page['Page']['keyword']) );

	}
	
	
	/**
 * 
 * 
*/
 function admin_index()
	{
		//$this->Page->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
				
		/*$this->paginate = array(
			'fields'=>array(
			'Page.id',
			'Page.title_per as title',
			'Page.created'
			),
			'limit' => $limit,
			'order' => array(
				'Page.id' => 'desc'
			)
		);
		
		$pages = $this->paginate('Page');
		$this->set(compact('pages'));*/
		$pages = $this->_indexgetCategories(0);
		$this->set(compact('pages'));
	}
	
	
  function admin_add(){
  	if($this->request->is('post'))
		{
			$this->Page->create();
			if($this->Page->save($this->request->data))
			{
				$this->Session->setFlash(__('the_page_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_page_could_not_be_saved'));
			}
		}
		
		 $pages = $this->_getCategories(0);
		 $this->set(compact('pages'));

  }	


	function admin_edit($id = null)
	{
		$lang = $_REQUEST['language'];
		$this->Page->id = $id;
		if(!$this->Page->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_page'));
			return;
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			if($this->Page->save($this->request->data))
			{
				$this->Session->setFlash(__('the_page_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_page_could_not_be_saved'));
			}
		}
		
		$options['fields'] = array(
			'Page.id',
			'Page.parent_id',
			'Page.title_'.$lang.' as title',
			'Page.body_'.$lang.' as body',
			'Page.keyword_'.$lang.' as keyword',
			'Page.meta_'.$lang.' as meta',
			'Page.status',
			'Page.arrangment'
		);
		$options['conditions'] = array(
			'Page.id'=>$id
		);
		$page = $this->Page->find('first',$options);
		$this->set(compact('page'));
		
		$pages = $this->_getCategories(0);
		$this->set(compact('pages'));
		
	}	

function admin_delete($id = null){		
		$this->Page->id = $id;
		if(!$this->Page->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_page'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->Page->delete($id))
		{
			$this->Session->setFlash(__('delete_page_success'), 'admin_success');
			$this->redirect(array('action'=>'index'));
		}else
		{
			$this->Session->setFlash(__('delete_page_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
  }
	
public function _getCategories($parent_id) {
		
			
	$category_data = array();
		
	$query=	$this->Page->find('all',array('fields' => array('id','parent_id','title_per as title'),'conditions' => array('parent_id' => $parent_id)));

			foreach ($query as $result) {
				$category_data[] = array(
					'id'    => $result['Page']['id'],
					'title' => $this->_getPath($result['Page']['id'])
				);

	         $category_data = array_merge($category_data, $this->_getCategories($result['Page']['id']));
			}	
		return $category_data;
	}
/**
* get name from category
* @param undefined $category_id
* 
*/	
 public function _getPath($category_id) {
		$query=	$this->Page->find('all',array('fields' => array('id', 'parent_id','title_per as title'),'conditions' => array('id' => $category_id)));

		foreach ($query as $category_info) {
		if ($category_info['Page']['parent_id']) {
			return $this->_getPath($category_info['Page']['parent_id']) .
			 " > " .$category_info['Page']['title'];			 
		} else {
			return $category_info['Page']['title'];
		}
		}
	} 
	
	
/**
 * get all childeren of category
 * @param undefined $parent_id
 * 
*/ 
  public function _indexgetCategories($parent_id) {	
	$category_data = array();		
	$query=	$this->Page->find('all',array('conditions' => array('parent_id' => $parent_id)));

			foreach ($query as $result) {
				$category_data[] = array(
					'id'    => $result['Page']['id'],
					'arrangment'    => $result['Page']['arrangment'],
					'status'    => $result['Page']['status'],
					'created'    => $result['Page']['created'],
					'title' => $this->_indexgetPath($result['Page']['id'])
				);
	         $category_data = array_merge($category_data, $this->_indexgetCategories($result['Page']['id']));
			}	
		return $category_data;
	}
/**
* get name from category
* @param undefined $category_id
* 
*/	
 public function _indexgetPath($category_id) {
		$query=	$this->Page->find('all',array('conditions' => array('id' => $category_id)));

		foreach ($query as $category_info) {
		if ($category_info['Page']['parent_id']) {
			return $this->_indexgetPath($category_info['Page']['parent_id']) .
			 " > " .$category_info['Page']['title_per'];			 
		} else {
			return $category_info['Page']['title_per'];
		}
		}
	}	
	
	
function get_main_pages()
{
	$options['fields'] = array(
			'Page.id',
			'Page.parent_id',
			'Page.title_per as title'
		);
	$options['conditions'] = array(
		'Page.status'=>1,
		'Page.parent_id'=>0
	);
	$options['order'] = array(
		'Page.arrangment'=>'asc'
	);
	$pages = $this->Page->find('all',$options);
	return $pages;
}	
	
function get_child_pages($id=null)
{
	$options['fields'] = array(
			'Page.id',
			'Page.title_per as title'
		);
	$options['conditions'] = array(
		'Page.status'=>1 ,
		'Page.parent_id'=>$id
	);
	$options['order'] = array(
		'Page.arrangment'=>'asc'
	);
	$pages = $this->Page->find('all',$options);
	return $pages;
}		
	
	
}

