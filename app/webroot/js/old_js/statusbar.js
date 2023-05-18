// status Bar
function statusBar(colo)
{
	var defaultColo = "red"
	
	//init: startToShow();
	if(colo)
	defaultColo=colo;
	
	startToShow();
	//set status : setStatus("red");
	setStatus(colo);
	
	$(".statusBar .main header .systemClose").click(function(e) {
        $(".statusBar").fadeOut(400);
    });
	$(".statusBar .Back").click(function(e) {
      //  $(".statusBar").fadeOut(400);
    });
}

function setStatus(colo)
{
	$(".statusBar .main").removeClass().addClass("main");;
	$(".statusBar .main").addClass(colo);
}

function startToShow()
{
	var delayy = 0;
	var backObj = $(".statusBar .Back");
	var mainObj = $(".statusBar .main");
	backObj.delay(delayy).hide(0);
	delayy+=50;
	backObj.delay(delayy).show(0);
	delayy+=50;
	backObj.delay(delayy).hide(0);
	delayy+=50;
	backObj.delay(delayy).show(0);
	delayy+=50;
	backObj.delay(delayy).animate({
		"left":0,
		"marginLeft":0,
		"width":"100%"
	},200);
	delayy+=200;
	backObj.delay(delayy).animate({
		"top":0,
		"marginTop":0,
		"height":"100%"
	},200);
	delayy+=1200;
	mainObj.delay(delayy).animate({
		"top":"50px",
		"right":"20%",
		"height":"350px",
		"width":"60%"
	},1000,"easeOutElastic");
}
