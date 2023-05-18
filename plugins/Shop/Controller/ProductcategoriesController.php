<?php


App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class ProductcategoriesController extends ShopAppController {

/*public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('view','get_child_productcategories');
}*/

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Productcategories';
	var $components = array('Httpupload');

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What product_category to display
 * @return void
 */

 function admin_index()
	{
		//$this->Productcategory->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
				
		/*$this->paginate = array(
			'fields'=>array(
			'Productcategory.id',
			'Productcategory.title as title',
			'Productcategory.created'
			),
			'limit' => $limit,
			'order' => array(
				'Productcategory.id' => 'desc'
			)
		);
		
		$productcategories = $this->paginate('Productcategory');
		$this->set(compact('productcategories'));*/
		$productcategories = $this->_indexgetCategories(0);
		$this->set(compact('productcategories'));
	}
	
	
  function admin_add(){
  	if($this->request->is('post'))
		{
			
			$data=Sanitize::clean($this->request->data);
			
			$file = $data['Productcategory']['image']; 
			if($file['size']>0)
				$output=$this->_picture();
			 
			if(!$output['error']) $this->request->data['Productcategory']['image']=$output['filename'];
				else $this->request->data['Productcategory']['image']='';	
				
			$this->Productcategory->create();
			if($this->Productcategory->save($this->request->data))
			{
				$this->Session->setFlash(__('the_product_category_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				if($file['size']>0)
			    {
					@unlink(__PRODUCT_CATEGORY_IMAGE_PATH."/".$output['filename']);
					@unlink(__PRODUCT_CATEGORY_IMAGE_PATH."/".__UPLOAD_THUMB."/".$output['filename']);
			 	}
				$this->Session->setFlash(__('the_product_category_could_not_be_saved'));
				$this->redirect(array('action' => 'index'));
			}
		}		
		 $productcategories = $this->_getCategories(0);
		 $this->set(compact('productcategories'));
  }	


	function admin_edit($id = null)
	{
		$this->Productcategory->id = $id;
		if(!$this->Productcategory->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_product_category'));
			return;
		}	
			
		if($this->request->is('post') || $this->request->is('put'))
		{
			
			$result= $this->Productcategory->findById($id);
			$data=Sanitize::clean($this->request->data);
			$file = $data['Productcategory']['image'];
			 	  
		    if($file['size']>0)
			 {
				
				$filename=$result['Productcategory']['image'];
				@unlink(__PRODUCT_CATEGORY_IMAGE_PATH."/".$filename);
				@unlink(__PRODUCT_CATEGORY_IMAGE_PATH."/".__UPLOAD_THUMB."/".$filename);
					
				$output=$this->_picture();
				 
				if(!$output['error']) $this->request->data['Productcategory']['image']=$output['filename'];
				else $this->request->data['Productcategory']['image']='';
			 }
			 else $this->request->data['Productcategory']['image']=$this->request->data['Productcategory']['old_image'];
			
			if($this->Productcategory->save($this->request->data))
			{
				$this->Session->setFlash(__('the_product_category_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
			   if($file['size']>0)
			    {
					@unlink(__PRODUCT_CATEGORY_IMAGE_PATH."/".$output['filename']);
					@unlink(__PRODUCT_CATEGORY_IMAGE_PATH."/".__UPLOAD_THUMB."/".$output['filename']);
			 	}
				$this->Session->setFlash(__('the_product_category_could_not_be_saved'));
			}
		}		
		$options['fields'] = array(
			'Productcategory.id',
			'Productcategory.parent_id',
			'Productcategory.title',
			'Productcategory.arrangment',
			'Productcategory.status',
			'Productcategory.image',
			'Productcategory.slug'
		);
		$options['conditions'] = array(
			'Productcategory.id'=>$id
		);
		$product_category = $this->Productcategory->find('first',$options);
		$this->set(compact('product_category'));
		
		$productcategories = $this->_getCategories(0);
		$this->set(compact('productcategories'));		
	}	

function admin_delete($id = null){		
		$this->Productcategory->id = $id;
		if(!$this->Productcategory->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_product_category'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		$this->Productcategory->Product->recursive = -1;
        
        $options['conditions'] = array(
			'Product.product_category_id' => $id
		);
		$count = $this->Productcategory->Product->find('count',$options);
		if($count>0){
            $this->Session->setFlash(__('category_includes_product_can_not_be_deleted'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));    
        }
    
        
		$this->Productcategory->recursive = -1;
        $options=array();
		$options['fields'] = array(
			'Productcategory.id',
			'Productcategory.image'
		   );
				   
		$options['conditions'] = array(
			'Productcategory.id'=>$id
		);
		$product_category = $this->Productcategory->find('first',$options);
		
		
		if($this->Productcategory->delete($id))
		{
			@unlink(__PRODUCT_CATEGORY_IMAGE_PATH."/".$product_category['Productcategory']['image']);
			@unlink(__PRODUCT_CATEGORY_IMAGE_PATH."/".__UPLOAD_THUMB."/".$product_category['Productcategory']['image']);
			
			$this->Session->setFlash(__('delete_product_category_success'), 'admin_success');
			$this->redirect(array('action'=>'index'));
		}else
		{
			$this->Session->setFlash(__('delete_product_category_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
  }
	
public function _getCategories($parent_id) {
		
			
	$category_data = array();
		
	$query=	$this->Productcategory->find('all',array('fields' => array('id','parent_id','title as title'),'conditions' => array('parent_id' => $parent_id)));

			foreach ($query as $result) {
				$category_data[] = array(
					'id'    => $result['Productcategory']['id'],
					'title' => $this->_getPath($result['Productcategory']['id'])
				);

	         $category_data = array_merge($category_data, $this->_getCategories($result['Productcategory']['id']));
			}	
		return $category_data;
	}
/**
* get name from category
* @param undefined $category_id
* 
*/	
 public function _getPath($category_id) {
		$query=	$this->Productcategory->find('all',array('fields' => array('id', 'parent_id','title as title'),'conditions' => array('id' => $category_id)));

		foreach ($query as $category_info) {
		if ($category_info['Productcategory']['parent_id']) {
			return $this->_getPath($category_info['Productcategory']['parent_id']) .
			 " > " .$category_info['Productcategory']['title'];			 
		} else {
			return $category_info['Productcategory']['title'];
		}
		}
	} 
	
	
/**
 * get all childeren of category
 * @param undefined $parent_id
 * 
*/ 
  public function _indexgetCategories($parent_id) {	
    $this->Productcategory->recursive = -1;
	$category_data = array();		
	$query=	$this->Productcategory->find('all',array('conditions' => array('parent_id' => $parent_id)));

			foreach ($query as $result) {
				$category_data[] = array(
					'id'    => $result['Productcategory']['id'],
					'arrangment'    => $result['Productcategory']['arrangment'],
					'status'    => $result['Productcategory']['status'],
					'created'    => $result['Productcategory']['created'],
					'title' => $this->_indexgetPath($result['Productcategory']['id'],'title'),
					'slug' => $this->_indexgetPath($result['Productcategory']['id'],'slug')
					 
				);
	         $category_data = array_merge($category_data, $this->_indexgetCategories($result['Productcategory']['id']));
			}	
		return $category_data;
	}
/**
* get name from category
* @param undefined $category_id
* 
*/	
 public function _indexgetPath($category_id,$title) {
		$query=	$this->Productcategory->find('all',array('conditions' => array('id' => $category_id)));

		foreach ($query as $category_info) {
		if ($category_info['Productcategory']['parent_id']) {
			return $this->_indexgetPath($category_info['Productcategory']['parent_id'],$title) .
			 " > " .$category_info['Productcategory'][$title];			 
		} else {
			return $category_info['Productcategory'][$title];
		}
		}
}	
	
	
function get_main_productcategories()
{
	$options['fields'] = array(
			'Productcategory.id',
			'Productcategory.parent_id',
			'Productcategory.title as title'
		);
	$options['conditions'] = array(
		'Productcategory.status'=>1,
		'Productcategory.parent_id'=>0
	);
	$options['order'] = array(
		'Productcategory.arrangment'=>'asc'
	);
	$productcategories = $this->Productcategory->find('all',$options);
	return $productcategories;
}	
	
function get_child_productcategories($id=null)
{
	$options['fields'] = array(
			'Productcategory.id',
			'Productcategory.title as title'
		);
	$options['conditions'] = array(
		'Productcategory.status'=>1 ,
		'Productcategory.parent_id'=>$id
	);
	$options['order'] = array(
		'Productcategory.arrangment'=>'asc'
	);
	$productcategories = $this->Productcategory->find('all',$options);
	return $productcategories;
}	

/**
* 
* 
*/
function _picture(){
  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['Productcategory']['image'];	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__PRODUCT_CATEGORY_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           
            $this->Httpupload->setmodel('Productcategory');
			$this->Httpupload->setuploaddir(__PRODUCT_CATEGORY_IMAGE_PATH);
			$this->Httpupload->setuploadname('image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_IMAGE_MAX_SIZE);
			$this->Httpupload->setimagemaxsize(__UPLOAD_IMAGE_MAX_WIDTH,__UPLOAD_IMAGE_MAX_HEIGHT);
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION;
			$this->Httpupload->create_thumb=true;
			$this->Httpupload->thumb_folder=__UPLOAD_THUMB;
			$this->Httpupload->thumb_width=190;
			$this->Httpupload->thumb_height=160; 
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     
        }
		else return array('error'=>true,'filename'=>'','message'=>'');		
	return array('error'=>false,'filename'=>$filename);
} 
 	
	
	
}

