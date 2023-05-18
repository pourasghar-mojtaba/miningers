<?php
App::uses('Component', 'Controller');
class GilaceAclComponent extends Component {
	
	public $components = array('Session');
	
	public function initialize(Controller $controller)
	{
	    $this->controller = $controller;
	}
	
	public function check_permision($params) {
	     //pr($params);
	  /*  echo $params['controller'];
	   echo $params['action'];
	   echo $params['prefix']; */
	   
	    if(isset($params['prefix'])){
		   if($params['prefix']=='admin'){
				if($params['action']=='admin_logout') return; 
				if($params['action']=='admin_dashboard') return;
				if($params['action']=='admin_login') return;
				if(!$this->check_role_permision($params['controller'],$params['action']))
				{
					$this->Session->setFlash(__('invalid_permision'), 'admin_error');
					$this->controller->redirect($this->controller->referer());
				}
		   } 
		}
		
   }	   
	   
	public function check_role_permision($controller,$action)
	{
		App::import('Model','User');
		$user= new User();
		$user->recursive = -1;
		
		$AdminUser_Info=$this->Session->read('AdminUser_Info');
       // pr($AdminUser_Info);
	
		if($AdminUser_Info['role_id']==1){
			return 1;
		}
		
        
        $sql="SELECT count(*) as count

										FROM `users` as User

									   inner join roles as Role
									           on Role.id=User.role_id
									   inner join aclroles as Aclrole
									           on Aclrole.role_id = Role.id 
									   inner join aclitems as Aclitem        
									   	    on Aclitem.id=Aclrole.aclitem_id	
									           
									 where User.id= ".$AdminUser_Info['id']."  
									   and Aclitem.controller = '".$controller."'
									   and Aclitem.action = '".$action."'
 		                                ";
        
        //pr($sql);
		$result = $user->query($sql); 	
		return $result['0']['0']['count'] ;														
	}    
	   
	public $AllowUserArray= array(
		 'display'
		,'send_friend_message'
		,'post_window'
		//,'get_user_info'
		,'add_post'
		,'refresh_home'
		,'refresh_new_home'
		,'user_list'
		,'follow'
		,'not_follow'
		,'edit_profile'
		,'edit_cover_image'
		,'edit_image'
		,'edit_account'
		,'edit_email'
		,'edit_password'
		,'block'
		,'add_comment'
		,'refresh_comment'
		,'search'
		,'ajax_search'
		,'logout'
		,'industry_box'
		,'like'
		,'unlike'
		,'message_box'
		,'read_message'
		,'delete_message'
		,'friend_autocomplete'
		,'friend_search'
		,'unblock'
		,'share'
		,'unshare'
		,'edit'
		//,'post_delete'
		,'new_message'
		,'send_one_message'
		,'view'
		,'index'
		,'new_message_info'
		,'new_follow_info'
		,'send_email'
		,'get_blog_tag'
		,'tags'
		,'last_tag_list',
		'refresh_new_post'
		,'updatetime'
		,'load_answer'
		,'new_tofollow_info'
		,'post_count'
		,'get_main_pages'
		,'industry_notification_multi'
		,'industry_notification_one'
		,'max_follow_notification_multi'
		,'max_follow_notification_one'
		,'student_notification_multi'
		,'student_notification_one'
		,'job_notification_multi'
		,'job_notification_one'
		,'post_notification_multi'
		,'post_notification_one'
		,'follow_follower_notification_multi'
		,'follow_follower_notification_one'
		,'favorite'
		,'unfavorite'
		,'refresh_favorite'
		,'send_infraction_report'
		,'send_infraction_report_post'
		,'disable_account'
		,'refresh_post_image'
		,'post_image'
		,'verify'
		,'add_comment_link'
		,'send_invitation_sms'
		,'view_learn'
		,'show_learn'
		,'follow_box'
		,'new_tag'
		,'all_tags'
		,'get_all_tags'
		,'delete_image'
		,'delete_cover_image'
		,'delete_pdf'
		,'ads_post_form'
		,'send_ads'
		,'postads_list'
		,'retry_pay'
		,'refresh_postads'
		,'tag_search'
		,'ads_count'
		,'add_usertag'
		/*,'get_share_post_users'*/
		,'load_inline_posts'
		,'refresh_view'
		,'invite'
		,'discover'
		,'refresh_discover'
		,'image_upload_window'
		,'edit_image_crop'
		,'cover_upload_window'
		,'edit_cover_crop'
		,'edit_pdf'
		,'verify_email'
		,'active_app'
		,'load_blog_form'
		,'add_blog'
		,'add_blog_save'
		,'edit_blog_save'
		,'delete_image'
		,'delete_blog'
		,'publish_blog'
		,'delete_commentblog'
		,'get_last_blog'
		,'new_post_data'
		,'get_my_blog'
	);
	
	public function member_allow(){
		$this->AllowUserArray;
	}



}
?>