<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class LibrariesController extends AppController {

  var $name = 'Libraries';
  var $components = array('Httpupload');

//var $helpers = array('Gilace');
//var $components = array('Gilace','Httpupload'); 
/**
 * Controller name
 *
	 
 /**
* follow to anoher user
* @param undefined $id
* 
*/



public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow();
}

/**
* 
* 
*/
 function admin_index()
	{
		$this->Library->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 50;
				
		if(isset($this->request->data['Library']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Library.title LIKE' => '%'.$this->request->data['Library']['search'].'%'),
				'fields'=>array(
    				'Library.id',
    				'Library.title',
                    'Library.year',
                    'Library.view',
    				'Library.arrangment',
    				'Library.status',
    				'Library.created'
				),
				'limit' => $limit,
				'order' => array(
					'Library.id' => 'asc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				'fields'=>array(
    				'Library.id',
    				'Library.title',
                    'Library.year',
                    'Library.view',
    				'Library.arrangment',
    				'Library.status',
    				'Library.created'
				),
				'limit' => $limit,
				'order' => array(
					'Library.id' => 'asc'
				)
			);
		}		
		$libraries = $this->paginate('Library');
		$this->set(compact('libraries'));
	}

/**
* 
* 
*/
function admin_add()
{		
	if($this->request->is('post') || $this->request->is('put'))
	{
        $datasource = $this->Library->getDataSource();
        $User_Info= $this->Session->read('AdminUser_Info');
        try{
            $datasource->begin();
            
             //pr($this->request->data);throw new Exception();
            $data=Sanitize::clean($this->request->data);
			$file = $data['Library']['pdf'];
			if($file['size']>0)
			  {			
				$output=$this->_upload_pdf();
				if(!$output['error']) {
					$this->request->data['Library']['pdf']=$output['filename'];
				}
				else {
					
					$this->request->data['Library']['pdf']='';
					throw new Exception(__($output['message']));
				}
			 }
			 else{
			 	$this->request->data['Library']['pdf']='';
			 }
             
             if(!$this->Library->save($this->request->data))
			        throw new Exception(__('the_library_not_saved'));
             $library_id= $this->Library->getLastInsertID();    
                   
			 /* tag proccess */	       
             if(isset($_POST['new_tags'])&& !empty($_POST['new_tags'])){
					$tags=$_POST['new_tags'];//explode('#',$this->request->data['Libraryrelatetag']['tag']);
				 
					$this->loadModel('Librarytag');
					if(!empty($tags)){
						foreach($tags as $tag)
						{
							$this->request->data['Librarytag']['title']= $tag;
							$this->Librarytag->create();
							
							if($this->Librarytag->save($this->request->data))
							{
								$tag_id[]=$this->Librarytag->getLastInsertID();					
							}
                            else throw new Exception(__('the_tag_not_saved'));
						}
					}
					
				}
				$data = array();
				if(isset($this->request->data['Libraryrelatetag']['library_tag_id'])){
					foreach($this->request->data['Libraryrelatetag']['library_tag_id'] as $id)
					{
						$dt=array('Libraryrelatetag' => array('library_id' => $library_id,'library_tag_id'=>$id));
						array_push($data,$dt);
					}
				}
				
				if(!empty($tag_id))
					{
						foreach($tag_id as $tid)
						{
							$dt=array('Libraryrelatetag' => array('library_id' => $library_id,'library_tag_id'=>$tid));
							array_push($data,$dt);
						}
						
					}
				 
				if(!empty($this->request->data['Libraryrelatetag']['library_tag_id']) || !empty($tag_id))
				{
					$this->Library->Libraryrelatetag->create();
					if(!$this->Library->Libraryrelatetag->saveMany($data))
                            throw new Exception(__('the_library_tag_not_saved'));
				}
			/* tag proccess */	
			
			/* author proccess */	       
             if(isset($_POST['new_authors'])&& !empty($_POST['new_authors'])){
					$authors=$_POST['new_authors'];//explode('#',$this->request->data['Libraryrelateauthor']['author']);
					$this->loadModel('Libraryauthor');
					if(!empty($authors)){
						foreach($authors as $author)
						{
							$this->request->data['Libraryauthor']['title']= $author;
							$this->Libraryauthor->create();
							
							if($this->Libraryauthor->save($this->request->data))
							{
								$author_id[]=$this->Libraryauthor->getLastInsertID();					
							}
                            else throw new Exception(__('the_author_not_saved'));
						}
					}
					
				}
				$data = array();
				if(isset($this->request->data['Libraryrelateauthor']['library_author_id'])){
					foreach($this->request->data['Libraryrelateauthor']['library_author_id'] as $id)
					{
						$dt=array('Libraryrelateauthor' => array('library_id' => $library_id,'library_author_id'=>$id));
						array_push($data,$dt);
					}
				}
				
				if(!empty($author_id))
					{
						foreach($author_id as $tid)
						{
							$dt=array('Libraryrelateauthor' => array('library_id' => $library_id,'library_author_id'=>$tid));
							array_push($data,$dt);
						}
						
					}
				 
				if(!empty($this->request->data['Libraryrelateauthor']['library_author_id']) || !empty($author_id))
				{
					$this->Library->Libraryrelateauthor->create();
					if(!$this->Library->Libraryrelateauthor->saveMany($data))
                            throw new Exception(__('the_library_author_not_saved'));
				}
			/* author proccess */	
			
                         
            
            
            $datasource->commit();
            
            $this->Session->setFlash(__('the_library_has_been_saved'), 'admin_success');
			$this->redirect(array('action' => 'index'));
        } catch(Exception $e) {
            $datasource->rollback();
			if($file['size']>0){
				@unlink(__USER_FILE_PATH."/".$output['filename']);
			}
			
            $this->Session->setFlash($e->getMessage(),'admin_error');
        }    
	}
	 
	
	
}


function _upload_pdf(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['Library']['pdf'];
	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__LIBRARY_FILE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
            
            $this->Httpupload->setmodel('Library');
			$this->Httpupload->setuploaddir(__LIBRARY_FILE_PATH);
			$this->Httpupload->setuploadname('pdf');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_PDF_MAX_SIZE);
			$this->Httpupload->allowExt= __UPLOAD_PDF_EXTENSION; 
			$this->Httpupload->create_thumb=false;
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
	return array('error'=>false,'filename'=>$filename);
}


function _picture($data,$index){
  App::uses('Sanitize', 'Utility');

  $output= array();  
	  
	  if($data['size']>0)
        {
			$ext=$this->Httpupload->get_extension($data['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__PRODUCT_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           
            $this->Httpupload->setmodel('Libraryimage');
			$this->Httpupload->setuploadindex($index);
			$this->Httpupload->setuploaddir(__PRODUCT_IMAGE_PATH);
			$this->Httpupload->setuploadname('image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_IMAGE_MAX_SIZE);
			$this->Httpupload->setimagemaxsize(__UPLOAD_IMAGE_MAX_WIDTH,__UPLOAD_IMAGE_MAX_HEIGHT);
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION;
			$this->Httpupload->create_thumb=true;
			$this->Httpupload->thumb_folder=__UPLOAD_THUMB;
			$this->Httpupload->thumb_width=180;
			$this->Httpupload->thumb_height=120; 
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
		else return array('error'=>true,'filename'=>'','message'=>'');
		
	return array('error'=>false,'filename'=>$filename);
}



/**
* 
* @param undefined $id
* 
*/
function admin_edit($id = null)
{
	$this->Library->recursive = -1; 
	$this->Library->id = $id;
	if(!$this->Library->exists())
	{
		$this->Session->setFlash(__('invalid_id_for_library'));
		$this->redirect(array('action' => 'index'));
	}
	
	$User_Info= $this->Session->read('AdminUser_Info');
	
	if($this->request->is('post') || $this->request->is('put'))
	{
	  
	   $datasource = $this->Library->getDataSource();
		try{
		    $datasource->begin();
			
			//pr($this->request->data);throw new Exception();
			
			$data=Sanitize::clean($this->request->data);
			$file = $data['Library']['pdf'];
			if($file['size']>0)
			  {
				$filename=$data['Library']['old_pdf'];
				@unlink(__LIBRARY_FILE_PATH."/".$filename);
				
				$output=$this->_upload_pdf();
				if(!$output['error']) {
					$this->request->data['Library']['pdf']=$output['filename'];
				}
				else {
					throw new Exception(__($output['message']));
					$this->request->data['Library']['pdf']='';
				}
			 }
			 else{
			 	$this->request->data['Library']['pdf']=$this->request->data['Library']['old_pdf'];
				
				if(!empty($this->request->data['Library']['delete_pdf']) && $this->request->data['Library']['delete_pdf']==1){
					@unlink(__LIBRARY_FILE_PATH."/".$this->request->data['Library']['old_pdf']);
					$this->request->data['Library']['pdf']='';
				}
				
			 }            
            
			if(!$this->Library->save($this->request->data))
			        throw new Exception(__('the_library_not_saved'));
				
			 // tag oprtion  
			    
                if(!$this->Library->Libraryrelatetag->deleteAll(array('Libraryrelatetag.library_id'=>$id),FALSE))
					throw new Exception(__('the_tag_not_saved'));
                             
					if(isset($_POST['new_tags'])&& !empty($_POST['new_tags'])){
						$tags=$_POST['new_tags'];//explode('#',$this->request->data['Libraryrelatetag']['tag']);
						 
						$tags=array_filter($tags,'strlen');
						$this->loadModel('Librarytag');
						if(!empty($tags)){
							foreach($tags as $tag)
							{
								$this->request->data['Librarytag']['title']= $tag;
								$this->Librarytag->create();
								
								if($this->Librarytag->save($this->request->data))
								{
									$tag_id[]=$this->Librarytag->getLastInsertID();					
								}else throw new Exception(__('the_tag_not_saved'));
							}
						}
						
					}
					$data = array();
					if(isset($this->request->data['Libraryrelatetag']['library_tag_id'])){
						foreach($this->request->data['Libraryrelatetag']['library_tag_id'] as $tagid)
						{
							$dt=array('Libraryrelatetag' => array('library_id' => $id,'library_tag_id'=>$tagid));
							array_push($data,$dt);
						}
					}
					
					if(!empty($tag_id))
						{
							foreach($tag_id as $tid)
							{
								$dt=array('Libraryrelatetag' => array('library_id' => $id,'library_tag_id'=>$tid));
								array_push($data,$dt);
							}
							
						}
					//pr($data);throw new Exception();
					 
					if($this->request->data['Libraryrelatetag']['library_tag_id'] || !empty($tag_id))
					{
                        if(!$this->Library->Libraryrelatetag->saveMany($data,array('deep' => true)))
							throw new Exception(__('the_library_tag_not_saved'));
					}			  
			 // tag opration
			 
			 // author oprtion  
			    
                if(!$this->Library->Libraryrelateauthor->deleteAll(array('Libraryrelateauthor.library_id'=>$id),FALSE))
					throw new Exception(__('the_author_not_saved'));
                             
					if(isset($_POST['new_authors'])&& !empty($_POST['new_authors'])){
						$authors=$_POST['new_authors'];//explode('#',$this->request->data['Libraryrelateauthor']['author']);
						$authors=array_filter($authors,'strlen');
						$this->loadModel('Libraryauthor');
						if(!empty($authors)){
							foreach($authors as $author)
							{
								$this->request->data['Libraryauthor']['title']= $author;
								$this->Libraryauthor->create();
								
								if($this->Libraryauthor->save($this->request->data))
								{
									$author_id[]=$this->Libraryauthor->getLastInsertID();					
								}else throw new Exception(__('the_author_not_saved'));
							}
						}
						
					}
					$data = array();
					if(isset($this->request->data['Libraryrelateauthor']['library_author_id'])){
						foreach($this->request->data['Libraryrelateauthor']['library_author_id'] as $authorid)
						{
							$dt=array('Libraryrelateauthor' => array('library_id' => $id,'library_author_id'=>$authorid));
							array_push($data,$dt);
						}
					}
					
					if(!empty($author_id))
						{
							foreach($author_id as $tid)
							{
								$dt=array('Libraryrelateauthor' => array('library_id' => $id,'library_author_id'=>$tid));
								array_push($data,$dt);
							}
							
						}
					//pr($data);throw new Exception();
					 
					if($this->request->data['Libraryrelateauthor']['library_author_id'] || !empty($author_id))
					{
                        if(!$this->Library->Libraryrelateauthor->saveMany($data,array('deep' => true)))
							throw new Exception(__('the_library_author_not_saved'));
					}			  
			 // author opration
			  

		    $datasource->commit();
			$this->Session->setFlash(__('the_technicalinfoitem_has_been_saved'), 'admin_success');
			$this->redirect(array('action' => 'index'));	
		} catch(Exception $e) {
			    $datasource->rollback();
				$this->Session->setFlash($e->getMessage(),'admin_error');
		}
		  	
	}
     
    
	$options['conditions'] = array(
		'Library.id' => $id		
		);
	$library = $this->Library->find('first',$options);
    $this->set('library', $library);
	
	
	$options=array();
	$this->Library->Libraryrelatetag->recursive=-1;
	$options['fields'] = array(
			'Libraryrelatetag.id',
			'Librarytag.title',
			'Librarytag.id' 
		   );
     $options['joins'] = array(
    		array('table' => 'librarytags',
        		'alias' => 'Librarytag',
        		'type' => 'INNER',
        		'conditions' => array(
        		'Librarytag.id = Libraryrelatetag.library_tag_id'
    		)
		)
    );	      
    $options['conditions'] = array(
		'Libraryrelatetag.library_id' => $id		
		);
	$libraryrelatetags = $this->Library->Libraryrelatetag->find('all',$options);
    $this->set('libraryrelatetags', $libraryrelatetags);
	 
	$options=array();
	$this->Library->Libraryrelateauthor->recursive=-1;
	$options['fields'] = array(
			'Libraryrelateauthor.id',
			'Libraryauthor.title',
			'Libraryauthor.id' 
		   );
     $options['joins'] = array(
    		array('table' => 'libraryauthors',
        		'alias' => 'Libraryauthor',
        		'type' => 'INNER',
        		'conditions' => array(
        		'Libraryauthor.id = Libraryrelateauthor.library_author_id'
    		)
		)
    );	      
    $options['conditions'] = array(
		'Libraryrelateauthor.library_id' => $id		
		);
	$libraryrelateauthors = $this->Library->Libraryrelateauthor->find('all',$options);
    $this->set('libraryrelateauthors', $libraryrelateauthors);

}



function admin_delete($id = null)
{
	$this->Library->recursive = -1;
	$options['fields'] = array(
		'Library.id',
		'Library.pdf'
	   );
			   
	$options['conditions'] = array(
		'Library.id'=>$id
	);
	$info = $this->Library->find('first',$options);
   
	$datasource = $this->Library->getDataSource();
	try{
    	$datasource->begin();   
	   //pr($images);throw new Exception();
	   if($this->Library->delete($id)){
		  @unlink(__LIBRARY_FILE_PATH."/".$info['Library']['pdf']);
	   }
	   else throw new Exception(__('the_library_not_deleted'));
	   
		$datasource->commit();
		 $this->Session->setFlash(__('the_library_deleted'), 'admin_success');
         $this->redirect(array('action' => 'index'));
	 } catch(Exception $e) {
	    $datasource->rollback();
		$this->Session->setFlash($e->getMessage(),'admin_error');
	}   
	   
}




/**
* 
* 
*/
 function admin_tag_search()
 {
 	$this->loadModel('Librarytag');
    $this->Librarytag->recursive = -1;
	
	$search_word =  $_GET["term"] ;
	$options['fields'] = array(
				'Librarytag.id',
				'Librarytag.title'
			   );
				   
	$options['conditions'] = array(
		   'Librarytag.title LIKE'=> "$search_word%" ,
	);
	
	$options['order'] = array(
			'Librarytag.id'=>'desc'
		);
	$options['limit'] = 5;
	
	$librarytags = $this->Librarytag->find('all',$options);
    $this->set('search_result',$librarytags);
    $this->render('/Elements/Librarytags/Ajax/tag_search','ajax'); 											 
 }
 
 /**
* 
* 
*/
 function admin_author_search()
 {
 	$this->loadModel('Libraryauthor');
    $this->Libraryauthor->recursive = -1;
	
	$search_word =  $_GET["term"] ;
	$options['fields'] = array(
				'Libraryauthor.id',
				'Libraryauthor.title'
			   );
				   
	$options['conditions'] = array(
		   'Libraryauthor.title LIKE'=> "$search_word%" ,
	);
	
	$options['order'] = array(
			'Libraryauthor.id'=>'desc'
		);
	$options['limit'] = 5;
	
	$libraryauthors = $this->Libraryauthor->find('all',$options);
    $this->set('search_result',$libraryauthors);
    $this->render('/Elements/Libraryauthors/Ajax/author_search','ajax'); 											 
 }
/**
* 
* 
*/
function _get_Technicalinfo(){
	
	$this->loadModel('Technicalinfo');
    $this->Technicalinfo->recursive = -1;
	
	$options['fields'] = array(
		'Technicalinfo.id ',
		'Technicalinfo.title'
	   );
	$response = $this->Technicalinfo->find('all',$options);
	
	return $response;
}

/**
* 
* 
*/
function index()
{
	
}



 
}
