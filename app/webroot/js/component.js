// JavaScript Document
function dropdown()
{
    var myTarget = $(".dropdown");
    $("> ul li",myTarget).hide(1);
    $('.dropdownBtn',myTarget).unbind('click');
    $('.dropdownBtn',myTarget).click(function(event) {
            var myDropdown = $(this).closest(myTarget);
    if ( $("> ul li",myDropdown).css('display')=='none')
    {
        $("> ul li",myDropdown).slideDown(400);
        event.stopPropagation();
    }
    else
    {
        $("> ul li",myDropdown).clearQueue();
        $("> ul li",myDropdown).slideUp(400);
    }
    });
}

	function dropdownCloser()
	{
		$(".dropdown > ul > li").slideUp(400);
	}
function tab2()
{
	var myTarget = $(".tab");
	$(".tabHeader li",myTarget).click(function(e) {
		if(!$(this).hasClass('active'))
		{
			$(".tabHeader li",myTarget).removeClass("active");
			var currentLi = $(".tabHeader li",myTarget).index(this);
			$(".tabContent > div",myTarget).fadeOut(400,function(){$(".tabContent div",myTarget).removeClass("active");})
			
			$(this).addClass("active");
			$(".tabContent > div",myTarget).eq(currentLi).delay(410).fadeIn(400,function(){$(".tabContent div",myTarget).eq(currentLi).addClass("active");})
		}
    });
}
function adminMessage(caller,text,color,fader)
{
	var randomClass = 'adminmsg'+Math.round(Math.random()*1000);
	$(caller).parent().prepend('<div class="adminMessage '+randomClass+' '+color+'">'+text+'</div>');
	if(fader){setTimeout(function(){$('.'+randomClass).fadeOut(400)},5000);}
}
function loading(address,element,params,callbackFunc,callBackFuncParams)
{
	 $(element).html('<div class="loadingPage"><div class="loaderCycle"></div><span>'+_loading+'</span></div>' );
     //   $(element).html('<span class="icon-spin1 animate-spin"></span>' );
	
	$.ajax({
		url:address,
                data:params,
		success:function(result){
			$(element).html(result);
            $('.loadingPage').fadeOut(400,function(){$("div.loaderCycle",element).remove();});			
                       // $("div.loaderCycle",element).remove();
			if(typeof callbackFunc !== "undefined") {
				if(typeof callBackFuncParams !== "undefined")
					callbackFunc(callBackFuncParams);
					else
					callbackFunc();
				}
		},
		error:function(result){alert('there is some errors . please refresh your page or try later...')},
		complete:function(result){
		}
		
	})
}
function popUp(address,params,callbackFunc,callBackFuncParams)
{
	$('body').prepend("<div id='modal'></div>");
	loading(address,$("#modal"),params,callbackFunc,callBackFuncParams);
	
	//single scroll bar
	$('body').css('overflow','hidden');
	$('.modalContent','#modal').css('overflow-x','scroll');
	
}
function modalCloser()
{
	$(".modalCloser").click(function(e) {
        $('#modal').fadeOut(400);
		setTimeout(function(){$("#modal").remove();},410)
		$('body').css('overflow','auto');
    });
}
function remove_modal(){
		$('#modal').fadeOut(400);
		setTimeout(function(){$("#modal").remove();},410)
		$('body').css('overflow','auto');
}
function selectOption()
{
	//this function active select option component:
	var myTarget = $(".selectOption");
	$(myTarget).click(function(event) {
        if( $(".selectOptionOptions",this).css("display")=="none")
		{
			$(".selectOptionOptions",this).slideDown(400);
			event.stopPropagation();
		}else
		{
			closeSelectOption();
		}		
    });
	$(".selectOptionOptions li",myTarget).click(function(e) {
        var parentSelectOption = $(this).parent().parent();
		$(".selectOptionData",parentSelectOption).val( $(this).text());
    });
}
	function closeSelectOption()
	{
		var myTarget = $(".selectOption");
		$(".selectOptionOptions",myTarget).slideUp(400);
	}
function makeCenterVer(objects)
{
	var screenHeight = $(objects).parent().height();
	var objectHeight = parseInt($(objects).height());
	var calcTop = Math.round(Math.abs((screenHeight-objectHeight)/2));
	$(objects).css("margin-top",calcTop);
}
function textBoxCounter(maxLenght)
{
	var myTarget = $('.textBoxCounter');
	$('textarea',myTarget).bind('input propertychange', function() {
        var parent = $(this).parent();
		var count =maxLenght - $('textarea',parent).val().length;
		$('.counter',parent).text( maxLenght - $('textarea',parent).val().length);
        //$('.counter',parent).text('100');
	});
		$('textarea',myTarget).focusin(function(e) {
			var parent = $(this).parent();
			$('.counter',parent).fadeIn(1000);
		});
		$('textarea',myTarget).focusout(function(e) {
			var parent = $(this).parent();
			$('.counter',parent).fadeOut(1000);
		});
}
function fileUpload()
{
	var myTarget = $('.fileUpload');
	$('.tile',myTarget).click(function(e) {
       var myParent = $(this).parent(); 
	   $('input[type="file"]',myParent).trigger('click');
    });

}


