
<?php
	// print_r($new_notifications);
	 if(!empty($new_notifications)){
	 	foreach($new_notifications as $new_notification)
		{
			echo"
				<div class='userList' id='new_notification_".$new_notification['User']['id']."'>
                <div class='userItem'>
                    <div class='profImage'>
                        <div style='float:right'>
							".$this->Gilace->user_image($new_notification['User']['image'],$new_notification['User']['sex'],$new_notification['User']['user_name'],'',60,60)."						
						</div>
                    </div>
                    <div class='profText'>
                        <div><strong>".$new_notification['User']['name']."</strong>
						<a class='atSign' href='".__SITE_URL.$new_notification['User']['user_name']."'>@".$new_notification['User']['user_name']."</a>
						</div>
                        <span>";
							switch($type){
						  	case 6:
								echo $new_notification['0']['post_count'].' '.__('post');
						  		break;
							case 5:
								echo $new_notification['0']['same_follower_count'].' '.__('same_follower');
						  		break;	
						  	default:
								echo $new_notification['0']['follow_count'].' '.__('chaser');
						  		break;
						  }
						echo " | ";
						 switch($type){
						  	case 3:
								echo $new_notification[0]['degree_title'].' - '.$new_notification['User']['university_name'];
						  		break;
							case 4:
								echo $new_notification['User']['job_title'].' - '.$new_notification['User']['company_name'];
						  		break;	
						  	default:
								echo $new_notification['Industry']['title'];
						  		break;
						  }
						echo"</span><br>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-xs-12'>
                        <div onclick='new_follow(".$new_notification['User']['id'].",".$type.")' class='btn classic'>
                            <span class='text'>".__('follow')."</span>
                            <span class='icon icon-left-open'></span>
                        </div>
                    </div>
                </div>
                <div class='clear'></div>
                <hr>
            </div>
			";
			
		}
	 }
?>
 <input type="hidden" id="last_notification_first" value="<?php echo $first; ?>" />
