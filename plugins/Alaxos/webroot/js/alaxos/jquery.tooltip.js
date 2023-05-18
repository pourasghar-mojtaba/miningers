/*
 * Tooltip 1.0 - jQuery plugin to show a tooltip on a hovered element
 *               the tooltip content is retrieved by Ajax first and then kept in page cache 
 * 
 * jQuery: developped on v1.6
 * 
 * Author: Nicolas Rod - www.alaxos.net
 * Date  : 2011-05
 */
(function($)
		{
	/***************************************
	 * main function that call internal plugin functions
	 */
	$.fn.tooltip = function(method){
		if(methods[method])
		{
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if(typeof method === 'object' || !method)
		{
			return methods.init.apply(this, arguments);
		}
		else
		{
			$.error("Method '" +  method + "' does not exist in jquery.tooltip.js");
		}
	};

	/***************************************
	 * plugin functions
	 */
	var methods = {

			'init'   : function(options){

				//default settings
				var settings = {

						'container_template'  : '<div id="tt_container"></div>',
						'content_template'    : '<div id="tt_content">{content}</div>',
						'loading_template'    : '<em>loading</em>',
						'container_id'        : "tt_container",
						'content_id'          : "tt_content",
						'url'                 : "",
						'content_element_id'  : "",
						'position'            : "br",
						'top_offset'          : 0,
						'left_offset'         : 0,
						'min_top'             : null,
						'min_left'            : null,
						'max_bottom'          : null,
						'max_right'           : null,
						'show_delay'          : 100,
						'show_time'           : 400,
						'hide_delay'          : 250,
						'hide_time'           : 500,
						'update_position'     : true,
						'update_position_time': 300,
						'allow_hovering'      : true,
						'show_on_hover'       : true,
						'preload'             : false,
						'preload_images'      : false,
						'shown'               : null,
						'hidden'              : null
				};

				//override default settings by given options
				var settings          = $.extend(settings, options);
				
				$.fn.tooltip.settings = settings;
				
				//debug(settings.position);
				
				return this.each(function() {
					
					$.fn.tooltip.settings.id = $(this).attr("id");
					
					/*
					 * Keep settings instance for the current element
					 * -> allow to retrieve specific settings in other methods such as show()
					 */
					$(this).data("settings", $.fn.tooltip.settings);
					
					if(settings.preload)
					{
						methods.preload.apply($(this));
					}
					
					if($.fn.tooltip.settings.show_on_hover)
					{
						/*
						 * Note do not user JQuery hover() function as it doesn't work with Firefox 
						 * when you enter/leave from/to a textarea 
						 */
						$(this).bind("mouseover.tooltip", function(){
	
							//debug($.fn.tooltip.hovered_id + " mouseover");
	
							$.fn.tooltip.hovered_id = $(this).attr("id");
	
							//debug($.fn.tooltip.hovered_id + " mouseover");
	
							show_tooltip($(this).attr("id"), settings);
						});
	
						$(this).bind("mouseout.tooltip", function(){
	
							//debug($.fn.tooltip.hovered_id + " mouseout");
	
							$.fn.tooltip.hovered_id = "";
	
							//debug($.fn.tooltip.hovered_id + " mouseout");
	
							hide_tooltip($(this).attr("id"), settings);
						});
					}
				}); 
			},
			
			'show'    : function(){
				
				var settings = this.data('settings');
				$.fn.tooltip.hovered_id = this.attr("id");
						
				show_tooltip(this.attr("id"), settings);
				
				return this;
			},
			
			'hide'    : function(){
				
				var settings = this.data('settings');
				$.fn.tooltip.hovered_id = "";
						
				hide_tooltip(this.attr("id"), settings);
				
				return this;
			},
			
			'preload' : function(){

				var settings = this.data('settings');
				load_url_content(this.attr("id"), settings, false);
				
				return this;
			},

			'set_content' : function(content){

				$.fn.tooltip.cached_tooltips[this.attr("id")] = content;
				
				return this;
			},

			'is_content_set' : function(){

				return $.fn.tooltip.cached_tooltips[this.attr("id")] != null;
			},
			
			'lock' : function(){

				$.fn.tooltip.locked = true;
				
				return this;
			},
			
			'unlock' : function(){

				$.fn.tooltip.locked = false;
				
				return this;
			}

	};

	/***************************************
	 * private vars
	 */
	$.fn.tooltip.hovered_id         = "";
	$.fn.tooltip.timer_show         = null;
	$.fn.tooltip.timer_hide         = null;
	$.fn.tooltip.visible_tooltip_id = "";
	$.fn.tooltip.cached_tooltips    = new Object();
	$.fn.tooltip.locked             = false;
	
	/***************************************
	 * private functions 
	 * 
	 */

	function show_tooltip(element_id, settings)
	{
		//debug("show tooltip for " + element_id + " with URL " + settings.url); 

		if($.fn.tooltip.locked == false)
		{
			if(typeof($.fn.tooltip.timer_show) == "object" || $.fn.tooltip.timer_show > 0)
			{
				clearTimeout($.fn.tooltip.timer_show);
			}
	
			$.fn.tooltip.timer_show = setTimeout(function(){
	
				if($.fn.tooltip.hovered_id.length > 0)
				{
					//debug("show tooltip for " + element_id + " with URL " + settings.url);
	
					var refresh        = false;
					var corrected_left = null;
					var corrected_top  = null;
	
					if($("#" + settings.container_id).length == 0)
					{
						$("body").append(settings.container_template);
						refresh = true;
	
						//debug("append container to the body with content: " + settings.container);
					}
	
					if($.fn.tooltip.visible_tooltip_id.length == 0 || ($.fn.tooltip.visible_tooltip_id.length > 0 && $.fn.tooltip.visible_tooltip_id != element_id))
					{
						/*
						 * if we hover another element than the currently displayed tooltip, we set
						 * it display to none to allow viewing fade in
						 */
						$("#" + settings.container_id).css("display", "none");
						refresh = true;
	
						//debug("set CSS display to none on " + $("#" + settings.container_id).attr("id"));
						
						if(($.fn.tooltip.visible_tooltip_id.length > 0 && $.fn.tooltip.visible_tooltip_id != element_id))
						{
							/*
							 * Fire eventual hidden callback on the element that has just been hidden above
							 */
							var old_settings = $("#" + $.fn.tooltip.visible_tooltip_id).data("settings");
							if(old_settings != undefined && old_settings.hidden)
							{
								old_settings.hidden();
							}
						}
					}
	
					$.fn.tooltip.visible_tooltip_id = element_id;
	
					if(refresh)
					{
						if($.fn.tooltip.cached_tooltips[element_id])
						{
							/*
							 * Content is taken from the page cache
							 */
							$("#" + settings.container_id).html($.fn.tooltip.cached_tooltips[element_id]);
	
							set_position(element_id, settings, false);
						}
						else
						{
							/*
							 * Content has never been shown -> must be created
							 */
	
							//you can customize the loading with for instance a spinner Gif through CSS
							$("#" + settings.container_id).html(settings.loading_template);
	
							if(settings.url.length > 0)
							{
								set_position(element_id, settings, true);
	
								$("#" + settings.container_id).fadeIn(settings.show_time, function(){
	
									debug("tooltip shown before loading content");
									load_url_content(element_id, settings, true);
									
								});
							}
							else if(settings.content_element_id.length > 0)
							{
								data = $("#" + settings.content_element_id).html();
								data = settings.content_template.replace("{content}", data);
	
								$("#" + settings.container_id).html(data);
	
								set_position(element_id, settings, false);
	
								$.fn.tooltip.cached_tooltips[element_id] = data;
	
							}
						}
					}
				}
	
			}, settings.show_delay);
		}
	}

	function load_url_content(element_id, settings, show_after_load)
	{
		$.ajax({  
			type: 'GET',
			async: true,
			url: settings.url,  

			success: function(data){  

				if(settings.url.toLowerCase().match("\.(jpg|jpeg|gif|png|bmp)$"))
				{
					data = settings.content_template.replace("{content}", '<img src="' + settings.url + '" alt="" />');
				}
				else
				{	
					data = settings.content_template.replace("{content}", data);
					
					if(!show_after_load && settings.preload_images)
					{
						/*
						 * Preload images 
						 */
						var img_sources = data.match(/src=(.+?[\.jpg|\.gif]")/gi);
						for(var k in img_sources)
						{
							var img = new Image();
							var src = img_sources[k].replace(/src=\"/, '').replace(/\"/, '');
							img.src = src;
						}
					}
				}

				if($.fn.tooltip.hovered_id == element_id && show_after_load)
				{
					$("#" + settings.container_id).html(data);

					//reset position as the tooltip may have another size with content inside
					set_position(element_id, settings, true);
				}

				$.fn.tooltip.cached_tooltips[element_id] = data;
			},

			error: function(request, textStatus, error){

				if(show_after_load)
				{
					$("#" + settings.content_id).html(error);
				}
			}
		});
	}

	function set_position(element_id, settings, faded_in)
	{
		/*
		 * Since the content of the tooltip is set, it has its definitive dimension
		 * -> we now calculate its position according to its width and height.
		 * Note: 
		 * 	with Mozilla family browsers, we could use right and bottom CSS properties to position the 
		 * 	tooltip when it is not positionned bottom-right, but it doesn't work with IE
		 * 	-> we fill the tooltip content before to position it
		 * 		-> it has a size
		 * 			-> we can calculate its position with top and left has we have a height and width 
		 */
		var tt_width       = $("#" + settings.container_id).width();
		var tt_height      = $("#" + settings.container_id).height();

		var element_pos    = $("#" + element_id).offset();
		var element_left   = Math.round(element_pos.left);
		var element_top    = Math.round(element_pos.top);

		element_left = element_left + settings.left_offset;
		element_top  = element_top + settings.top_offset;

		var element_width  = $("#" + element_id).width();
		var element_height = $("#" + element_id).height();

		var padding_left   = $("#" + settings.container_id).css("padding-left").replace("px", "");
		var padding_right  = $("#" + settings.container_id).css("padding-right").replace("px", "");
		var padding_top    = $("#" + settings.container_id).css("padding-top").replace("px", "");
		var padding_bottom = $("#" + settings.container_id).css("padding-bottom").replace("px", "");

		var corrected_left  = null;
		var corrected_top   = null;

		if(settings.position == "bl") //bottom left
		{	
			//debug(element_id + ": [element_id.height: " + element_height + "][element_id.left: " + element_left + "][tooltip.width: " + tt_width + "][tooltip.height: " + tt_height + "]");

			var left = element_left - tt_width - padding_left - padding_right;
			var top  = element_top  + element_height;

			/*
			 * Calculate an eventual new position if the tooltip is outside of the visible area
			 */
			if(left < $(window).scrollLeft())
			{
				corrected_left = $(window).scrollLeft() + 5;
			}

			if((top + tt_height) > ($(window).height() + $(window).scrollTop()))
			{
				corrected_top = $(window).height() - tt_height - padding_bottom - padding_top - 5 + $(window).scrollTop();
			}
		}
		else if(settings.position == "tl") //top left
		{
			var left = element_left - tt_width - padding_left - padding_right;
			var top  = element_top  - tt_height - padding_top - padding_bottom;

			/*
			 * Calculate an eventual new position if the tooltip is outside of the visible area
			 */
			if(left < $(window).scrollLeft())
			{
				corrected_left = $(window).scrollLeft() + 5;
			}

			if(top < $(window).scrollTop())
			{
				corrected_top = $(window).scrollTop() + 5;
			}
		}
		else if(settings.position == "tr") //top right
		{	
			var left = element_left + element_width;
			var top  = element_top  - tt_height - padding_top - padding_bottom;

			/*
			 * Calculate an eventual new position if the tooltip is outside of the visible area
			 */
			if((left + tt_width) > ($(window).width() + $(window).scrollLeft()))
			{
				corrected_left = left - ((left + tt_width) - $(window).width()) - padding_left - padding_right - 5 + $(window).scrollLeft();
			}

			if(top < $(window).scrollTop())
			{
				corrected_top = $(window).scrollTop() + 5;
			}
		}
		else if(settings.position == "centered") //tooltip centered hover the element
		{
			var padding_horizontal = (parseInt(padding_left) + parseInt(padding_right)) / 2;
			var padding_vertical = (parseInt(padding_top) + parseInt(padding_bottom)) / 2;

			var left = element_left + (element_width/2)  - (tt_width/2) - padding_horizontal;
			var top  = element_top  + (element_height/2) - (tt_height/2) - padding_vertical;

			/*
			 * Calculate an eventual new position if the tooltip is outside of the visible area
			 */
			if((left + tt_width) > ($(window).width() + $(window).scrollLeft()))
			{
				corrected_left = left - ((left + tt_width) - $(window).width()) - padding_left - padding_right - 5 + $(window).scrollLeft();
			}
			else if(left < $(window).scrollLeft())
			{
				corrected_left = $(window).scrollLeft() + 5;
			}

			if(top < $(window).scrollTop())
			{
				corrected_top = $(window).scrollTop() + 5;
			}
			else if((top + tt_height) > ($(window).height() + $(window).scrollTop()))
			{
				corrected_top = $(window).height() - tt_height - padding_bottom - padding_top - 5 + $(window).scrollTop();
			}
		}
		else	// default is bottom right ("br")
		{
			var left = element_left + element_width;
			var top  = element_top  + element_height;

			/*
			 * Calculate an eventual new position if the tooltip is outside of the visible area
			 */
			if((left + tt_width) > ($(window).width() + $(window).scrollLeft()))
			{
				corrected_left = left - ((left + tt_width) - $(window).width()) - padding_left - padding_right - 5 + $(window).scrollLeft();
			}

			if((top + tt_height) > ($(window).height() + $(window).scrollTop()))
			{
				corrected_top = $(window).height() - tt_height - padding_bottom - padding_top - 5 + $(window).scrollTop();
			}
		}

		//debug(element_id + ": [left: " + left + "][top: " + top + "]");

		/*
		 * Takes care of position limits
		 */
		if(settings.max_bottom != null && top + tt_height > settings.max_bottom) {top = settings.max_bottom - tt_height;}
		if(settings.min_top != null    && top < settings.min_top)                {top = settings.min_top;}
		if(settings.max_right != null  && left + tt_width > settings.max_right)  {left = settings.max_right - tt_width;}
		if(settings.min_left != null   && left < settings.min_left)              {left = settings.min_left;}
		
		if($("#" + settings.container_id).is(':animated')) 
		{
			//stop animation as it would update the left and top values after they have been set
			$("#" + settings.container_id).stop(false, true);
		}

		$("#" + settings.container_id).css("left", left + "px");
		$("#" + settings.container_id).css("top",  top  + "px");

		if(!faded_in)
		{
			//debug("tooltip will be shown after positionning");
			
			if(settings.shown)
			{
				$("#" + settings.container_id).fadeIn(settings.show_time, function(){
					
						/*
						 * Fire shown event only if the element fadingIn has not been replaced by another in the meantime
						 */
						if($.fn.tooltip.visible_tooltip_id == element_id)
						{
							settings.shown();
						}
					});
			}
			else
			{
				$("#" + settings.container_id).fadeIn(settings.show_time);
			}
		}

		//debug(element_id + ": [corrected_left: " + corrected_left + "][corrected_top: " + corrected_top + "]");

		if(settings.update_position)
		{
			/*
			 * Update tooltip position if it is outside the screen
			 */
			if(corrected_left != null && corrected_top != null)
			{
				$("#" + settings.container_id).animate({"left": "+=" + (corrected_left - left) + "px", "top": "+=" + (corrected_top - top) + "px"}, settings.update_position_time);
			}
			else if(corrected_left != null)
			{
				$("#" + settings.container_id).animate({"left": "+=" + (corrected_left - left) + "px"}, settings.update_position_time);
			}
			else if(corrected_top != null)
			{
				$("#" + settings.container_id).animate({"top": "+=" + (corrected_top - top) + "px"}, settings.update_position_time);
			}
		}

		if(settings.allow_hovering)
		{
			register_container_events(element_id, settings);
		}
	}

	/**
	 * Register events to allow tooltip hovering
	 */
	function register_container_events(element_id, settings)
	{
		/*
		 * Note do not user JQuery hover() function as it doesn't with Firefox 
		 * when you enter/leave from/to a textarea 
		 */
		$("#" + settings.container_id).bind("mouseover.tooltip",
				function(){

			$.fn.tooltip.hovered_id = element_id;

		});

		$("#" + settings.container_id).bind("mouseout.tooltip",
				function(){

			$.fn.tooltip.hovered_id = "";
			hide_tooltip(element_id, settings);

		});
	}

	function hide_tooltip(element_id, settings)
	{
		//debug("enter hide_tooltip() tooltip for " + element_id);
		if($.fn.tooltip.locked == false)
		{
			if(typeof($.fn.tooltip.timer_hide) == "object" || $.fn.tooltip.timer_hide > 0)
			{
				clearTimeout($.fn.tooltip.timer_hide);
			}
	
			if($.fn.tooltip.visible_tooltip_id.length == 0 || $.fn.tooltip.visible_tooltip_id == element_id)
			{	
				$.fn.tooltip.timer_hide = setTimeout(function(){
	
					//debug(1);
	
					if($.fn.tooltip.hovered_id == "")
					{
						//debug("fadeOut() element_id: '" + element_id + "', hovered_id: '" + $.fn.tooltip.hovered_id + "'");
	
						if(settings.hidden)
						{
							$("#" + settings.container_id).fadeOut(settings.hide_time, settings.hidden);
						}
						else
						{
							$("#" + settings.container_id).fadeOut(settings.hide_time);
						}
						$.fn.tooltip.visible_tooltip_id = "";
	
						//debug("fadeOut() tooltip for " + element_id + " with URL " + settings.url);
					}
	
				}, settings.hide_delay);
			}
			else if($.fn.tooltip.visible_tooltip_id != element_id)
			{
				$.fn.tooltip.timer_hide = setTimeout(function(){
	
					debug("2");
	
					if($.fn.tooltip.visible_tooltip_id != "" && $.fn.tooltip.visible_tooltip_id != element_id)
					{
						/*
						 * Another tooltip than the current displayed has to be shown
						 * => we hide the currently displayed one without fading it out
						 */
						$("#" + settings.container_id).remove();
						$.fn.tooltip.visible_tooltip_id = "";
	
						//debug("remove() tooltip for " + element_id + " with URL " + settings.url);
					}
	
				}, 0);
			}
			else
			{
				//debug(element_id + " won't fadeOut");
			}
		}
	}

	/*
	 * print in Firebug console
	 */
	function debug(message)
	{
		if(window.console)
		{
			window.console.log("jquery.tooltip debug: " + message);
		}
	}


		})( jQuery );
