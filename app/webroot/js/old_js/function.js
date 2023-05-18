// JavaScript Document
function initial()
{
	//init
	/*$("input[type='text']").val("");
	$("textarea").val("");*/
	
	// muti select code
			MultiSelectFunc();

	//top search box
	LBLOverTXB("#header .content .searchBar .searchM .searchBar_form label","#header .content .searchBar .searchM .searchBar_form input[type='text']")
	LBLOverTXB("div.mainData .content .LoginBox .main tr label","div.mainData .content .LoginBox .main tr .input input")
	LBLOverTXB("div.mainData .content .LogIns .main tr label:nth-child(2)","div.mainData .content .LogIns .main tr input[type='text']:nth-child(2)")
//	LBLOverTXB("div.mainData .content .Register .main tr label","div.mainData .content .Register .main tr input")
	LBLOverTXB("#NewPost .newComnt label","#NewPost .newComnt .commnetTXB");
	LBLOverTXB(".searchPanels form.search label",".searchPanels form.search input[type='text']");
	//character counter
	$(".commentTXB1").keydown(function(e) {
		var numb = 200 - $(this).val().length;
        $(".commentCounter1").text(numb);
    });
	$(".commentTXB2").keydown(function(e) {
		var numb = 200 - $(this).val().length;
        $(".commentCounter2").text(numb);
    });
	
	//extend post
	$(".expand").click(function(e) {
        $("#extendPost").fadeIn(400);
    });
	$("#extendPost .postBody .extraBtn .close").click(function(e) {
        $("#extendPost").fadeOut(400);
    });
	$("#extendPost .BG").click(function(e) {
        $("#extendPost").fadeOut(400);
    });
	
	//gilace logo animation
	$("#footer #gilace").mouseenter(function(e) {
        $(this).animate({
			opacity:1,
			bottom:"-15px"
		},400);
    });
	$("#footer #gilace").mouseleave(function(e) {
        $(this).animate({
			opacity:0.5,
			bottom:"-70px"
		},400);
    });
	
	//search page search box
	$(".searchPanels form.search .searchBox2 > .more").click(function(e) {
        $(".searchPanels form.search > p").slideDown(300);
    });
	$(".searchPanels form.search > p label").click(function(e) {
		var current = $(this);
		var imgSrc = $("img",current).attr("src");
		$('form.search input[type="submit"]').css("background-image","url("+imgSrc+")");
		$(".searchPanels form.search > p").slideUp(300);
    });
	
	//visit card sliding
	$("#content .profileHeader .coverImage").mouseenter(function(e){
		$("#content .profileHeader .coverImage .item1").animate(
	{
		//opacity:0.1,
		right:"700px"
	},1000,"easeOutBounce");
	});
	$("#content .profileHeader .coverImage").mouseleave(function(e){
		
		$("#content .profileHeader .coverImage .item1").animate(
	{
		//opacity:1,
		right:"0"
	},1000,"easeOutBounce",function(){$(this).clearQueue();});
	});
	
	//new message :add to message list
	$("#MessageBox .contentTag .newMSG ul.friendsList li .add").click(function(e) {
		var titles = $(this).parent().prop("title");
		$("#MessageBox .contentTag .newMSG form input[type='text']").val(titles);
    });
	
	$(".newMSG_btn").click(function(e) {
        $("#MessageBox .contentTag ul.MessageList").fadeOut(200);
		$("#MessageBox .contentTag .newMSG").delay(200).fadeIn(200);
    });
	
	//new message :show contacts 
	$("#MessageBox .contentTag .newMSG form .receiver_show").click(function(e) {
        $("#MessageBox .contentTag .newMSG .space_warper").slideDown(200);
		$("#MessageBox .contentTag .newMSG ul.friendsList").delay(200).fadeIn(200);
    });
	//new message :cancel contacts 
	$("#MessageBox .contentTag .newMSG form .cancel").click(function(e) {
        $("#MessageBox .contentTag .newMSG").slideUp(200);
		$("#MessageBox .contentTag ul.MessageList").delay(200).fadeIn(200);
	});
	
	//submenu switch
	$(".help").click(function(event) {
		var a=$(this).parent().find(".submenu").first();
        if(a.css("display")=="none")
		{
			a.slideDown(200);
			event.stopPropagation();
		}
		else
		{
			a.slideUp	(200);
		}
    });
	
	//profile submenu switch
	$(".menuBtn").click(function(event) {
		var a=$(this).parent().find(".submenu").first();
        if(a.css("display")=="none")
		{
			a.slideDown(200);
			event.stopPropagation();
		}
		else
		{
			a.slideUp	(200);
		}
    });
	
	//reportMenu config
	/*$(".subMenu1").click(function(event) {
        var obj = $(this).parent().find(".submenu2");
		obj.animate({
			width:"200px",
			opacity:1
			},200,function(){})
		event.stopPropagation();
    });
	$(".submenu2 .back").click(function(e) {
        var obj = $(this).parent();
		obj.animate({
			width:"0",
			opacity:0
			},200)
    });*/
	
	
	//close mennu event
	$('html').click(function() {
	//Hide submenu
		var obj = $(".submenu2");
		obj.animate({width:"0",opacity:0},200)
		$('.submenu').slideUp(200);
		$("li","ul.SelectOption").each(function(index, element) {
			if(index >0){
               $(this).slideUp(400);
            }
			
		});
        
	});
	
	//help article images
	$("#content .mainData article img").mouseenter(function(e) {
        $(this).animate({opacity:0},200);
		$(this).delay(200).animate({opacity:1},200)
    });
	$("#content .mainData article img").mouseleave(function(e) {
        $(this).animate({opacity:0.5},200);
    });
	
	//scroll top of page
	$(document).scroll(function()
	{
		if($(this).scrollTop()==0)
		{
			$("#goToTop").fadeOut("fast");
		}
		else
		{
			$("#goToTop").fadeIn("fast");
		}
	})
	$("#goToTop").click(function() {
	  $("html, body").animate({ scrollTop: 0 }, "fast");
	  $("#goToTop").fadeOut("fast");
	});
	
	//click on comment Btn
	$("#extendPost .postBody > ul.icons li.comment_btn").click(function(e) {
		$("html, body").scrollTo('#extendPost .postBody > .newComnt form .commentTXB2',{duration:'400'});
		$('#extendPost .postBody > .newComnt form .commentTXB2').trigger("focus");
    });

	
	//post btn configs
	$("#content .profileContent ul.posts li").mouseenter(function(e) {
        $(".extraBtn", this).fadeIn(150);
    });
	$("#content .profileContent ul.posts li").mouseleave(function(e) {
        $(".extraBtn", this).fadeOut(150);
    });
	
	//message box close/open
	$("#messageBtn").click(function(e) {
        $(".BlackBack").fadeIn(200);
		$("#MessageBox").delay(200).fadeIn(200);
    });
	$("#MessageBox .close").click(function(e) {
        $(".BlackBack").fadeOut(200);
		$("#MessageBox").delay(200).fadeOut(200);
    });
	
	//search filter animation
	$(".rightPanel ul.resultGroup li").mouseenter(function(e) {
        $(this).animate(
	{
		marginRight:"5px"
	},100);
    });
	$(".rightPanel ul.resultGroup li").mouseleave(function(e) {
        $(this).animate(
	{
		marginRight:"0"
	},100);
    });
	
	//close this func
	$(".closethis").click(function(e) {
        $(this).parent().fadeOut(200);
    });
	
	//add new post animation
	$(".newNews").click(function(e) {
        if($("#NewPost").css("display")=="none")
		{
			fadeInNewPost();
		}
		else
		{
			fadeOutNewPost();
		}
        
    });
	
	//especial checkBox
		$(".especialCeckBox").each(function(index, element) {
			var especialCheckBox = $(this).parent().find('input[type="checkbox"]:first')
			if(especialCheckBox.prop("checked"))
			{
				$(this).addClass("checked");
			}else
			{
				$(this).removeClass("checked");
			}
        });
		$(".especialCeckBox").click(function(e) {
			var especialCheckBox = $(this).parent().find('input[type="checkbox"]:first')
			if($(this).hasClass("checked"))
			{
				$(this).removeClass("checked");
				especialCheckBox.prop("checked","");
				$('#search_with_email').val(0);
			}else
			{
				$(this).addClass("checked");
				especialCheckBox.prop("checked","checked");
				$('#search_with_email').val(1);
			}
		});
	
	//search page
	//showfilter
	$(".searchPanels span.showFilter").click(function(e) {
        if($(".searchPanels form.industeries").css("display")=="none")
		{
			$(".searchPanels form.industeries").show(200);
		}else
			$(".searchPanels form.industeries").hide(200);
    });
	 /* show right industry */
	$("#view_all_indusrey").click(function(e) {
        if($("#right_industry_panel").css("display")=="none")
		{
			$("#right_industry_panel").show(200);
		}else
			$("#right_industry_panel").hide(200);
    });
	
	 
	
	//searchresult show follow btn
	$(".searchResult ul.result li").mouseenter(function(e) {
        $(".btn",this).animate(
	{
		top:"5"
	},500,"easeOutElastic");
    });
	$(".searchResult ul.result li").mouseleave(function(e) {
        $(".btn",this).animate(
	{
		//opacity:1,
		top:"-40"
	},500,"easeOutElastic");
    });
	
	//loader rotate
    rotate();    // run it!
	
	//login form open close
	$(".loginBtn").click(function(e) {
        $(".loginForm").slideDown(200);
    });
	$(".loginForm .close").click(function(e) {
        $(".loginForm").slideUp(200);
    });
	
	
	
	//set maximize func :
		$(".post .maximize").click(function(e) {
			if($(this).hasClass("minimize"))
			{
				minimize($(this));
			}
			else
				maximize($(this));
		});
	
	//show outOfBox icons
		$(".post").mouseenter(function(e) {
            $(".outOfBox",this).fadeIn(400);
        });
		$(".post").mouseleave(function(e) {
            $(".outOfBox",this).fadeOut(400);
        });	
	//selectOption
		selectOption();	
}

function maximize(s)
{
	var sender =s;
	var parentPost = sender.parent().parent();
	
	//remove some minimized attributes :
	sender.addClass("minimize");
	$(".post .imagePlace").hide();
	$(".answerBox").hide();
	$(".answerPost").hide();
	$(".post.active").each(function(index, element) {
		$(this).addClass("inactive");
		$(this).removeClass("active");
	});
	parentPost.removeClass("inactive");
	
	//add new maximized attributes :
	parentPost.addClass("active");
	
		//get answer tags
	var answerPost = parentPost.nextUntil(".post.mainPost",".post");
	answerPost.show();

	var x = parentPost.find(".imagePlace")
	x.slideDown(400);
	rotate180(sender);
}
function minimize(s)
{
	var sender = s;
	var parentPost = sender.parent().parent();
	
	//remove some minimized attributes :
	sender.removeClass("minimize");
	
	$(".post .imagePlace").hide();
	$(".answerBox").hide();
	$(".answerPost").hide();
	$(".post.active").each(function(index, element) {
		$(this).addClass("inactive");
		$(this).removeClass("active");
	});
	rotate360(sender);
}

function rotate180(classNam)
{
	var deg = 0;
	window.setInterval(function() {
	if(deg < 180)
	{
		rotate1(deg+=10,classNam);
	}
}, 40);}
function rotate360(classNam)
{
	var deg = 180;
	window.setInterval(function() {
	if(deg < 360)
	{
		rotate1(deg+=10,classNam);
	}
}, 40);}
function rotate1(degree,classNam) {
// For webkit browsers: e.g. Chrome
   classNam.css({ WebkitTransform: 'rotate(' + degree + 'deg)'});
// For Mozilla browser: e.g. Firefox
   classNam.css({ '-moz-transform': 'rotate(' + degree + 'deg)'});
        }


function rotate()
{
	var deg = 0;
window.setInterval(function() {
	
	
	if(deg <360)
	{
		rotate1(deg+=10);
	}
	else
	{
		deg =9;
		rotate1(deg);
	}
 
}, 40);}
function rotate1(degree) {
// For webkit browsers: e.g. Chrome
   $(".loader").css({ WebkitTransform: 'rotate(' + degree + 'deg)'});
// For Mozilla browser: e.g. Firefox
   $(".loader").css({ '-moz-transform': 'rotate(' + degree + 'deg)'});
}

function fadeOutNewPost()
{
	$("#NewPost .newComnt .attachment").fadeOut(200);
	$("#NewPost .newComnt .icons").fadeOut(200);
	$("#NewPost .newComnt span").fadeOut(200);
	
	$("#NewPost .newComnt .commnetTXB").delay(200).slideUp(200);
	$("#NewPost .newComnt label").delay(200).slideUp(200);

	$("#NewPost .newComnt").delay(400).fadeOut(1);
	$("#NewPost").delay(400).fadeOut(100);
}
function fadeInNewPost()
{
	$("#NewPost").fadeIn(100);
	$("#NewPost .newComnt").fadeIn(1);
	
	$("#NewPost .newComnt .commnetTXB").delay(100).slideDown(200);
	$("#NewPost .newComnt label").delay(100).slideDown(200);
	
	$("#NewPost .newComnt .attachment").delay(300).fadeIn(200);
	$("#NewPost .newComnt .icons").delay(300).fadeIn(200);
	$("#NewPost .newComnt span").delay(300).fadeIn(200);
}

function LBLOverTXB(lbl,txb)
{
	 
	if($(txb).val()!="" && $(txb).val()!=undefined)  { $(txb).prev().fadeOut(400);};
	 
		$(txb).focus(function(){
			$(this).prev().fadeOut(400);
		})
		$(txb).focusout(function(){
			if( $(this).val() == "")
			$(this).prev().fadeIn(400);
		})
		$(lbl).click(function(){
			$(this).next().trigger("focus");
		})
	var a = $(txb).val()
	if(a != "")
	$(lbl).css("display","none");
	
	
}
function MultiSelectFunc()
{
	$(".MultiSelect li:nth-child(1)").click(function(e) {
		if($(this).parent().find("li:nth-child(2)").css("display")=="none")
		{
			$(this).parent().find("li").fadeIn(100);
		}else
		{
			$(this).parent().find("li").each(function(index, element) {
                if(index>0)
				{
					$(this).fadeOut(100);
				}
            });
		}
    });
	$(".MultiSelect li input[type='checkbox']").change(function(e) {
        if( $(this).is(":checked") )
		{
			$(this).parent().css("margin-right","20px");
		}
        else
		{
			$(this).parent().css("margin-right","0px");
		}
		
		var a = $(".MultiSelect li input[type='checkbox']:checked");
		if(a.length >3)
		{
			$(this).prop("checked","");
			$(this).parent().css("margin-right","0px");
		}
    });
	
}

function selectOption()
{
	//init option
	var selectOption = "ul.SelectOption";
	var speed = 400;
    $("li",selectOption).each(function(index, element) {
        if(index >0)
		$(this).slideUp(speed);
    });
	$("li.header span",selectOption).text($("li:nth-child(2)",selectOption).text())
	
	$("li",selectOption).click(function(e) {
		$("li.header span",selectOption).text($(this).text());
		$("li.header span",selectOption).attr("title",$(this).attr("title"));
		var action =$(this).attr("role");
		$('#SearchForm').attr("action",action);
		$("li",selectOption).each(function(index, element) {
			if(index >0)
			$(this).slideUp(speed);
		});
    });
	$("li.header").click(function(event) {
		if($("li:last",selectOption).css("display")=="none")
                {
        	  $("li",selectOption).slideDown(speed);
                   event.stopPropagation();
                }else
			$("li",selectOption).each(function(index, element) {
				if(index >0)
				$(this).slideUp(speed);
			});
    });
	
}

function show_report_menu(post_id)
{
		 $('#submenu_report_'+post_id).animate({
			width:"200px",
			opacity:1
			},'slow',function(){
				// animation complete
			}); 
		 
	 	
}




