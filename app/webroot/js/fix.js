// JavaScript Document
function fullHeight(imput)
{
	//fix home page forms container height
	
	$(imput).height($(this).parent().height());
//	alert($(imput).parent().height())
}
function makeCenterVer(objects)
{
	var screenHeight = $(objects).parent().height();
	var objectHeight = parseInt($(objects).height());
	var calcTop = Math.round(Math.abs((screenHeight-objectHeight)/2));
	$(objects).css("margin-top",calcTop);
}

