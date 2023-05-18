<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class HomeimagesController extends AppController {


   var $name = 'Homeimages';
   var $components = array('Httpupload');
   
   public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('get_home_image');
  }
   
/**
* 
* 
*/
function get_home_image()
 {
	$this->Homeimage->recursive = -1;	
	$response=	$this->Homeimage->query(
	  "	
	  		select * from (
				         (	
				    	    SELECT * FROM `home_images` 
								WHERE  '".date('Y-m-d h:i', time())."' between from_date and to_date
					      )
					      union all
					      (	
					        SELECT * FROM `home_images` 
							WHERE  '".date('Y-m-d h:i', time())."' >= from_date 
						          and  (to_date='' or to_date ='0000-00-00 00:00:00') 
					      )
				)  as Homeimage 
				where status = 1
				order by arrangment,from_date asc
				limit 0,1
	  "
	 );
			 
	return $response;
 }



   function admin_index()
	{
		$this->Homeimage->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 50;
				
		if(isset($this->request->data['Homeimage']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Homeimage.title LIKE' => '%'.$this->request->data['Homeimage']['search'].'%'),
				'fields'=>array(
				'Homeimage.id',
				'Homeimage.title',
				'Homeimage.from_date',
				'Homeimage.to_date',
				'Homeimage.arrangment',
				'Homeimage.status',
				'Homeimage.created'
				),
				'limit' => $limit,
				'order' => array(
					'Homeimage.arrangment' => 'asc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				'fields'=>array(
				'Homeimage.id',
				'Homeimage.title',
				'Homeimage.from_date',
				'Homeimage.to_date',
				'Homeimage.arrangment',
				'Homeimage.status',
				'Homeimage.created'
				),
				'limit' => $limit,
				'order' => array(
					'Homeimage.arrangment' => 'asc'
				)
			);
		}		
		$home_images = $this->paginate('Homeimage');
		$this->set(compact('home_images'));
	}
  

function admin_add()
	{
				
		if($this->request->is('post') || $this->request->is('put'))
		{
			/*$User_Info= $this->Session->read('AdminUser_Info');
			$this->request->data['Homeimage']['user_id']= $User_Info['id'];*/
			$data=Sanitize::clean($this->request->data);
			$file = $data['Homeimage']['image']; 	  
		    if($file['size']>0)
			 {
				$output=$this->_picture();
				if(!$output['error']) $this->request->data['Homeimage']['image']=$output['filename'];
				else $this->request->data['Homeimage']['image']='';
				
				if($this->Homeimage->save($this->request->data))
				{
					$this->Session->setFlash(__('the_home_image_has_been_saved'), 'admin_success');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					if($file['size']>0)
				    {
						@unlink(__HOME_IMAGE_PATH."/".$output['filename']);
				 	}
					$this->Session->setFlash(__('the_home_image_not_saved'),'admin_error');
				}
			 }
		}
	}
 
/**
* 
* @param undefined $id
* 
*/ 
 function admin_edit($id = null)
	{
		$this->Homeimage->id = $id;
		if(!$this->Homeimage->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_post'));
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			 $error=FALSE;
			 $result= $this->Homeimage->findById($id);
			 if($error==FALSE){
			   $data=Sanitize::clean($this->request->data);
			   $file = $data['Homeimage']['image'];
			 	  
			   if($file['size']>0)
				 {
					
					$filename=$result['Homeimage']['image'];
					@unlink(__HOME_IMAGE_PATH."/".$filename);
						
					$output=$this->_picture();
					if(!$output['error']) $this->request->data['Homeimage']['image']=$output['filename'];
					else $this->request->data['Homeimage']['image']='';
				 }
				 else $this->request->data['Homeimage']['image']=$this->request->data['Homeimage']['old_image'];
				 
			   if($this->Homeimage->save($this->request->data))
				{
					$this->Session->setFlash(__('the_home_image_has_been_saved'), 'admin_success');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					if($file['size']>0)
				    {
						@unlink(__HOME_IMAGE_PATH."/".$output['filename']);
						@unlink(__HOME_IMAGE_PATH."/".__UPLOAD_THUMB."/".$output['filename']);
				 	}
					$this->Session->setFlash(__('the_home_image_not_saved'),'admin_error');
				}	
			}	 
		}
		$this->_set_home_image($id);
	}
 /**
 * 
 * @param undefined $id
 * 
*/   
function _set_home_image($id)
	{
		$this->Homeimage->recursive = -1;
		$this->Homeimage->id = $id;
		if(!$this->Homeimage->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_home_image'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this->Homeimage->findById($id);
	    }
	    
	    $this->set('homeimage', $this->request->data);
	    
	    return $this->request->data;
	}    
    
/**
* 
* @param undefined $id
* 
*/
function admin_delete($id = null)
{
	$this->Homeimage->recursive = -1;
	$options['fields'] = array(
		'Homeimage.id',
		'Homeimage.image'
	   );
			   
	$options['conditions'] = array(
		'Homeimage.id'=>$id
	);
	$post = $this->Homeimage->find('first',$options);
   
	   if($this->Homeimage->delete($id)){
	   	try{
		   	 @unlink(__HOME_IMAGE_PATH."/".$post['Homeimage']['image']);
		   } catch (Exception $e) {
		   	
		   }
		   $this->Session->setFlash(__('the_home_image_deleted'), 'admin_success');
           $this->redirect(array('action' => 'index'));
	   }
	   else $this->Session->setFlash(__('the_home_image_not_deleted'),'admin_error');
}

 
 /**
 * 
 * 
*/
 function _picture(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['Homeimage']['image'];
	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__HOME_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           
            $this->Httpupload->setmodel('Homeimage');
			$this->Httpupload->setuploaddir(__HOME_IMAGE_PATH);
			$this->Httpupload->setuploadname('image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_IMAGE_MAX_SIZE);
			$this->Httpupload->setimagemaxsize(__UPLOAD_IMAGE_MAX_WIDTH,__UPLOAD_IMAGE_MAX_HEIGHT);
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION;
			/*$this->Httpupload->create_thumb=true;
			$this->Httpupload->thumb_folder=__UPLOAD_THUMB;
			$this->Httpupload->thumb_width=100;
			$this->Httpupload->thumb_height=70; */
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




