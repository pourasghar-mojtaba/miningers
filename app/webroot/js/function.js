// JavaScript Document
function init()
{
	dropdown();
	selectOption();
	htmlClick();
	tab2();
	appsShow();
	gilace();
	
}

//control all html close click
function htmlClick()
{
	$('html').click(function() {
		//1)Hide submenu
		closeSelectOption();
		dropdownCloser();
		
		//apps closer
		 $('#apps').slideUp(400);
	});
}
function HasBeenScrolledTo(elem) {
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + 250;

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}
/*function animationPage2()
{
	var myTarget = $('.page2');
	$('.obj3',myTarget).css({'right':'-100%','opacity':0});
	$('.obj2',myTarget).css({'right':'-100%','opacity':0});
	$('.obj1',myTarget).css({'left':'-100%','opacity':0});
	$('.obj3',myTarget).css({'display':'block'});
	$('.obj2',myTarget).css({'display':'block'});
	$('.obj1',myTarget).css({'display':'block'});
	
	
	$('.obj2',myTarget).animate({
		right:'',
		opacity:1
		},400);
	var myTarget = $('.page2');
	$('.obj3',myTarget).delay(500).animate({
		right:'',
		opacity:1,
		top:'200px'
		},400);
	$('.obj1',myTarget).delay(1000).animate({
		left:'',
		opacity:1
		},400);
}*/
function animationPage2()
{
	var myTarget = $('.page2');
	$('.obj2',myTarget).css({'right':'-100%','opacity':0});
	$('.obj1',myTarget).css({'left':'-100%','opacity':0});
	$('.obj2',myTarget).css({'display':'block'});
	$('.obj1',myTarget).css({'display':'block'});
	
	
	$('.obj2',myTarget).animate({
		right:'',
		opacity:1
		},400);
	var myTarget = $('.page2');
	$('.obj1',myTarget).delay(1000).animate({
		left:'',
		opacity:1
		},400);
}
function setSearchOption(hid,plac,action)
{
	$('.searchBox input[type="search"]').prop('placeholder',plac);
	$('.searchBox input[type="hidden"]').val(hid);
	$('#SearchForm').attr("action",action);
	
}
function showSocialActivities()
{
	$('.post').mouseenter(function(e) {
		$('footer',this).clearQueue();
        $('footer',this).animate({backgroundColor:'#3d9ad1'},400);
;
    });
	
	$('.post').mouseleave(function(e) {
		$('footer',this).clearQueue();
        $('footer',this).animate({backgroundColor:'transparent'},400);
    });
	
}
function appsShow()
{
	$('.allApps').click(function(event) {
        $('#apps').slideDown(400);
		event.stopPropagation();
    });
}
function postsImage()
{
	var target = $('.post .media .imgBox');
	$('.tile',target).click(function(e) {
        var parent = $(this).closest(target);
		if($('img',parent).css('display')==='none')
			$('img',parent).show(400);
		else
			$('img',parent).hide(400);
    });
}
function showBox(caller,element)
{
	var myParent = $(caller).parent();
	var targetParent = $(element).parent();
	
	$(myParent).children().removeClass('active');
	$(caller).addClass('active');
	
	$(targetParent).children().hide(10);
	$(element).show(10);
}
function gilace()
{
	$('#gilace').mouseenter(function(e) {
        $('.logo.inactive',this).fadeOut(400);
    });
	$('#gilace').mouseleave(function(e) {
		$('.logo.inactive',this).clearQueue();
        $('.logo.inactive',this).fadeIn(400);
    });
}
