
if(typeof(jQuery) != "undefined")
{
    jQuery(document).ajaxSend(function(event, xhr, settings) {
    
    	function getCookie(name) {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }
    
    	if(/^(POST|PUT)$/.test(settings.type))
    	{
    		if(typeof(console) != "undefined")
    		{
    			console.log(settings.type + " request is gonna be sent with CSRF header '" + getCookie("CakeCookie[csrftoken]") + "'");
    		}
    		
    		xhr.setRequestHeader("X-CSRFToken", getCookie("CakeCookie[csrftoken]"));
    	}
    });
}