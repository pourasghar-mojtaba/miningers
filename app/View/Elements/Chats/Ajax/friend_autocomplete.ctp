  
 
<script>
var tarr =[];
var arr = [];	 
$("#friend_input").autocomplete({
					//define callback to format results
					
					source: function(req, add){
					
						//pass request to server
						$.getJSON(_url+"chats/friend_search?callback=?", req, function(data) {
							
							//create array for response objects
							var suggestions = [];
							//var suggestions1 = [];
							//process response
							$.each(data, function(i, val){								
								suggestions.push({'name':val.name,'id':val.user_id,'image':val.image});
								 
								//suggestions.push(val.name+'<input type=hidden id='+val.user_id+'>');
							 
							});
							
							//pass array to callback
							
							add(suggestions);
							 
							 
						});
					},
					select: function(e, ui) {
						
						//create formatted friend
						var friend = ui.item.value;
						var user_count= $("#friends span").length+1; 
						/*
						tarr.push(friend);
						 alert(tarr.length);
						for (var i=0;i<=tarr.length;i++)
						{
							 if(i>0){
							 	if(tarr[i]==friend){
								showmsg(_not_repeated_user_to_send_message);return false;
							 }else tarr=arr;
							 }  
						}
						arr.push(friend);
						tarr=arr;*/
						if(user_count>5){
							showmsg(_selected_max_user);return;
						}
						var id= ui.item.id;
							span = $("<span>").text(friend),
							a = $("<a>").addClass("remove").attr({
								href: "javascript:",
								title: "Remove " + friend,
								id   : id,
							}).text("x").appendTo(span);
						
						//add friend to friend div
						 
						span.insertBefore("#friend_input");
					},
					
					//define select handler
					change: function() {
						
						//prevent 'to' field being updated and correct position
						$("#friend_input").val("").css("top", 2);
					}
				});
				
				//add click handler to user_ids div
				$("#friends").click(function(){
					
					//focus 'to' field
					$("#friend_input").focus();
				});
				
				//add live handler for clicks on remove links
				$(".remove", document.getElementById("friends")).live("click", function(){
				
					//remove current friend
					$(this).parent().remove();
					
					//correct 'to' field position
					if($("#friends span").length === 0) {
						$("#friend_input").css("top", 0);
					}				
				});	
		
		
			</script>
            
