<?php
/**
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array('Cookie','Session','RequestHandler','Auth','GilaceAcl');
	public $helpers = array('Html','Form','Session','Text','Time','Paginator','Html' => array('className' => 'MyHtml'));

	
	public function beforRender()
	{
		$this->_configureErrorLayout();
		parent::beforRender();
		if($this->RequestHandler->isMobile()){
			//$this->layout='mobile';
			$this->theme='mobile';
		}
		 
	}
    	
	public function _configureErrorLayout() {
		if ($this->name == 'CakeError') {			
			if ($this->_isAdminMode()) {
				$this->layout = 'admin';
			} else {
				$this->layout = 'default';
			}
		}
	}
 
	public function _isAdminMode() {
		$adminRoute = Configure::read('Routing.prefixes');
		if (isset($this->params['prefix']) && in_array($this->params['prefix'], $adminRoute)) {
			return true;
		}
		return false;
	}
	
	
	public function beforeFilter() {
		 
	  	
	  $this->_setLanguage();
      
      $this->GilaceAcl->check_permision($this->request->params);
	  
        
      if(!isset($this->request->params['admin']))  
	 	  {
		  	
			if($this->Cookie->check('User')){
				if (!$this->Session->check('Auth.User.id'))
				{
					$user_info=$this->Cookie->read('User');
					$this->request->data['User']['email']=$user_info['Email'];
					$this->request->data['User']['password']=$user_info['Pass'];		
					if(!$this->Auth->login()) $this->Cookie->delete('User'); 
				}
			}
		  } 
		else 
		{
			//$this->Session->write('Config.language','per');
		}
		   
		
		 
	  if ($this->request->is('ajax'))
       {
           Configure::write('debug', 0);
           $this->layout = 'ajax';
           $this->autoRender = false;
       }
	   
	    $this->Auth->authorize      = array('Actions' => array('actionPath' => 'controllers'));
 
		if(isset($this->request->params['admin']) && ($this->request->params['prefix'] == 'admin'))
		{
			$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'admin' => TRUE);
			$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login', 'admin' => TRUE);
			//$this->Auth->loginRedirect = array('controller' => 'orders', 'action' => 'index', 'admin' => true);
			
			$this->Auth->authenticate = array(
			AuthComponent::ALL => array(
				'userModel' => 'User',
				'fields' => array(
					'username' => 'email',
					'password' => 'password'
					),
				'scope' => array(
					'User.status' => 1,
					'User.role_id <>'=>2   /* admin */  
					)
				), 'Form'
			);
			 
		}
		else
		{
			
			 $this->Auth->loginAction = array('controller' => 'pages', 'action' => 'display', 'admin' => false);
			//$this->Auth->loginRedirect = array('controller' => 'orders', 'action' => 'index', 'admin' => true);
			 $this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'display', 'admin' => false);
		
			$this->Auth->authenticate = array(
			AuthComponent::ALL => array(
				'userModel' => 'User',
				'fields' => array(
					'username' =>  'email',
					'password' => 'password'
					),
				'scope' => array(
					'User.status' => 1/*,
					'User.role_id'=>2     /* user role */
					)
				), 'Form'
			);
		}
		

		if(isset($this->request->params['admin']) && ($this->request->params['prefix'] == 'admin')) {
			 
			if(!$this->_check_admin_login_user())
				{
					$this->layout = 'adminlogin';
					$this->Auth->allow('admin_login','admin_logout');
					
				}	
			else 
			  {
			  	  $this->layout = 'admin';
				  $this->Auth->allow('admin_dashboard','admin_logout','admin_index','admin_active_permission','admin_inactive_permission','admin_add','admin_edit','admin_delete','admin_tag_search','admin_user_export','admin_onedate_export','admin_alldate_export','admin_manager');
			  }		  	

		} else {
			 
			if(!$this->_check_login_user())
			{
			  //pr($this->request->params);exit();
			  if( ($this->request->params['controller']=='pages' && $this->request->params['action']=='display') /*||
			  	  ($this->request->params['controller']=='users' && $this->request->params['action']=='register')*/
			    )
			  {
			  	$this->layout = 'logoutuser';
			  }
			  $this->Auth->allow(array('display','ajax_register','check_email','check_captcha','forget_pass','register_send_email','confirmation_email','captcha_image','email','login','test','app_login','get_blog_tag','user_count','madaner_posts','register_form','get_main_pages','app_register'));
			}
			  
			else
			{
				
				$this->Auth->allow($this->GilaceAcl->member_allow());
			}  
		}
	      
   }	

	private function _setLanguage() {
	    
		//pr($this->params['language']); exit;
		
		
		if ($this->Cookie->read('lang')) {
	        $this->Session->write('Config.language', $this->Cookie->read('lang'));
			$locale=$this->Cookie->read('lang');
			$this->set('locale',$locale);		
	    } 
	    //if the user clicked the language URL
		
	    if (isset($this->params['language'])&&($this->params['language'] !=  
			$this->Session->read('Config.language')))
		 {
	    	//then update the value in Session and the one in Cookie
	        $this->Session->write('Config.language', $this->params['language']);
	        $this->Cookie->write('lang', $this->params['language'], false, '2000 days');
			$locale=$this->Cookie->read('lang');
			$this->set('locale',$locale);
		
	     }
		 $lang=$this->Cookie->read('lang');
		 if(!isset($lang)){
			$this->Session->write('Config.language', 'per');
	        $this->Cookie->write('lang','per', false, '2000 days');
			$this->set('locale','per');
			//pr($this->Session->read('Config.language')); exit;
		}
		/*
		if(isset($lang) && ($lang != 'per'|| $lang !='eng')){
			$this->Cookie->delete('lang');
			$this->Session->write('Config.language', 'eng');
	        $this->Cookie->write('lang','eng', false, '2000 days');
			$this->set('locale','eng');
		}*/
		
		
		if(!isset($this->params['language']) && $this->Cookie->read('lang')=='')
		{
		 	
			$locale = Configure::read('Config.language');
			//echo(Configure::read('Config.language'));
			$this->Session->write('Config.language',$locale);
			$this->set('locale',$locale);
            if ($this->Session->check('Config.language')) {
                Configure::write('Config.language', $this->Session->read('Config.language'));
            } 
             
		 }
		/*
		if(trim(strtolower($_SERVER['HTTP_HOST']))=='miningers.com'){
		//if(trim(strtolower($_SERVER['HTTP_HOST']))=='localhost'){	
			$this->Session->write('Config.language', 'eng');
	        $this->Cookie->write('lang','eng', false, '2000 days');
			$this->set('locale','eng');
		}*/
		
		if($this->Session->read('Config.language')=='img'){
			$this->Session->write('Config.language', 'per');
	        $this->Cookie->write('lang','per', false, '2000 days');
			$this->set('locale','per');
		} 
	}

   
   /**
   * check user for login
   * 
*/
   function _check_login_user()
   {
		if($this->Session->check('User_Info')){
			return TRUE;
		}
		return FALSE;
   }
   
   /**
   * check user for login
   * 
*/
   function _check_admin_login_user()
   {
		if($this->Session->check('AdminUser_Info')){
			return TRUE;
		}
		return FALSE;
   }
   
   
   public function backup($tables = '*') {
        $this->layout = $this->autoLayout = $this->autoRender = false;
		$return='';
        if ($tables == '*') {
            $tables = array();
            $result = $this->query('SHOW TABLES');
            while ($row = mysql_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        foreach ($tables as $table) {
            $result = $this->query('SELECT * FROM ' . $table);
            $num_fields = mysql_num_fields($result);

            $return.= 'DROP TABLE ' . $table . ';';
            $row2 = mysql_fetch_row($this->query('SHOW CREATE TABLE ' . $table));
            $return.= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysql_fetch_row($result)) {
                    $return.= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = ereg_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $return.= '"' . $row[$j] . '"';
                        } else {
                            $return.= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return.= ',';
                        }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }
        $handle = fopen(__BackUp_Path.'/db-backup-' . time() . '-' . (md5(implode(',', $tables))) . '.sql', 'w+');
        fwrite($handle, $return);
        fclose($handle);
    } 
   
}
