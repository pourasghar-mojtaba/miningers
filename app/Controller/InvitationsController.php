<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email'); 
class InvitationsController extends AppController {

 
/**
 * Controller name
 *
 * @var string
 */
 var $components = array('SMS');
/**
* 
* 
*/	 
function send_email()
{
	$response['success'] = false;
	$response['message'] = null;
	$error=FALSE;
	
	$User_Info= $this->Session->read('User_Info');
	
	$email_list=$_REQUEST['email'];
	$email_arr=explode(',',$email_list);
	
	if(count($email_arr)>3){
		$response['success'] = false;
		$response['message']=__('you_can_send_3email');
		$this->set('ajaxData', json_encode($response));
		$this->render('/Elements/Invitations/Ajax/ajax_result','ajax');	
		return FALSE;
	}

	$Email = new CakeEmail();
	
	foreach($email_arr as $toemail)
	{
		try{
			$Email->reset();
			$Email->template('invitation_sendemail', 'sendemail_layout');
			$Email->subject(__('send_invitation'));	
			$Email->emailFormat('html');
			$Email->to($toemail);
			$Email->from(array(__Madaner_Email => __Email_Name));
			$Email->viewVars(array('from_name'=>$User_Info['name'],'from_user_name'=>$User_Info['user_name']));
			$Email->send();
            $response['success'] = TRUE;
		    $response['message']=__('send_invitation_success');
		} catch (Exception $e) {
			$response['success'] = false;
			$response['message']=__('cant_send_email');
			$error=TRUE;
		}
	}	 
	 
    $this->set('ajaxData', json_encode($response));
	$this->render('/Elements/Invitations/Ajax/ajax_result','ajax');	
	 
} 
 
 /**
 * 
 * 
*/
function send_invitation_sms()
{
	$response['success'] = false;
	$response['message'] = null;
	$error=FALSE;
	
	$User_Info= $this->Session->read('User_Info');
	
	$sms_list=$_REQUEST['sms'];
	$sms_arr=explode(',',$sms_list);
	
	if(count($sms_arr)>3){
		$response['success'] = false;
		$response['message']=__('you_can_send_3sms');
		$this->set('ajaxData', json_encode($response));
		$this->render('/Elements/Invitations/Ajax/ajax_result','ajax');	
		return FALSE;
	}

	foreach($sms_arr as $tosms)
	{
		try{
			$resp=$this->SMS->GetUserBalance();
            $sms_response=$this->SMS->SendSMS($User_Info['name'].__('invitation_sms_content'),$this->SMS->change_to_validnum($tosms),'normal') ;
            if($sms_response['state']=='error'){
                $response['success'] = false;
    			$response['message']=__('cant_send_email');
    			$error=TRUE; 
                // set log 
    				Controller::loadModel('Errorlog');
    				/*$this->Errorlog->get_log('InvitationsController Send Invitation SMS from = '$User_Info['name'].' ,to mobile ='.$tosms,$sms_response['message']);*/
                    $this->Errorlog->get_log('InvitationsController SMS','from = '.$User_Info['name'].' to mobile = '.$tosms.'message='.$sms_response['message']);
    		  // set log
            }
            else{
                $response['success'] = TRUE;
		        $response['message']=__('send_invitation_success');
            }
            
		} catch (Exception $e) {
			$response['success'] = false;
			$response['message']=__('cant_send_email');
			$error=TRUE;
		}
        //sleep(100);
	}	 
	 
    $this->set('ajaxData', json_encode($response));
	$this->render('/Elements/Invitations/Ajax/ajax_result','ajax');	
	 
}  

function invite(){
	$this->set('title_for_layout',__('invite_from_friend'));
	$this->set('description_for_layout',__('invite_from_friend'));
	$this->set('keywords_for_layout',__('invite_from_friend'));
	$is_error = FALSE;
	if(($this->request->is('post') || $this->request->is('put')) && !empty($_POST['send_invite']))
	{
		$this->request->data=Sanitize::clean($this->request->data);
		$User_Info= $this->Session->read('User_Info');
		
		$this->set('request',$this->request->data);
		
		if(!empty($this->request->data)){
			$this->loadModel('User');
            $this->User->recursive = -1;
			$err_emails = array();
			
			$recive_emails = $this->request->data['email'];
			$recive_names = $this->request->data['name'];
			foreach($recive_emails as $key=>$recive_email){
				if($recive_email =='' || $recive_names[$key]==''){
					unset($recive_emails[$key]);
					unset($recive_names[$key]);
				}
			}			 
			if(empty($recive_emails)){
				$this->Session->setFlash(__('not_exist_email_to_invite'),'error');
				$this->redirect('/invitations/invite');
			}
            // CHECK emails  
            foreach($recive_emails as $key=>$toemail) {  
                $options = array();
                $options['fields'] = array(
    			    'User.user_name',
    			    'User.email',
        		 );
    			$options['conditions'] = array(
    			    'User.email'=> $toemail
    			);	   
    			$user= $this->User->find('first',$options);
                if(!empty($user)){
                    //$is_error=TRUE;
                    $err_emails[]=array('user_name'=>$user['User']['user_name'],'email'=>$user['User']['email']);
                }
            }    
            $this->set('err_emails',$err_emails);
            //pr($err_emails);
			if(!$is_error){
                $Email = new CakeEmail();
    			foreach($recive_emails as $key=>$toemail) {
					if(!in_array($toemail,$err_emails)){
						$register_key = __SITE_URL.'users/register?ragid='.md5($User_Info['id']);
	    				try{
	    					$Email->reset();
	    					$Email->template('invitation_sendemail', 'sendemail_layout');
	    					$Email->subject(__('send_invitation'));	
	    					$Email->emailFormat('html');
	    					$Email->to($toemail);
	    					$Email->from(array(__Madaner_Email => __Email_Name));
	    					$Email->viewVars(array('from_name'=>$User_Info['name'],'from_user_name'=>$User_Info['user_name'],'to_name'=>$recive_names[$key],'register_key'=>$register_key));
	    					$Email->send();
	                        $this->Session->setFlash(__('send_invite_email_success'),'success');
	    				} catch (Exception $e) {
	    					$this->Session->setFlash(__('cant_send_invite_email'),'error');
	    				}
					}    				
    			}
                $this->set('request',array());
            }
		}
		
	}
}
 
 
}









