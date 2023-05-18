/********************************************************************
 * Author: Nicolas Rod, nico@alaxos.com 
 * website: www.alaxos.com, www.alaxos.ch
 */

/********************************************************************
 * Global constants 
 */
var INVALID_DATE             = "INVALID_DATE";
var INVALID_DATE_FIELD_COLOR = "#ff0000";
var INVALID_DATE_TEXT_COLOR  = "#ffffff";

/********************************************************************
 * Global variables 
 */
var valid_field_colors = new Array();

/********************************************************************
 * Number fields 
 */

function format_number(textbox, is_float)
{
	var valid_numeric_chars = "0123456789";
	if(is_float)
	{
		valid_numeric_chars += ".";
	}

	value = textbox.value;
	
	newvalue = "";
	non_numeric_found = false;
	dot_found = false;
	var i;
	for(i = 0; i < value.length; i++)
	{
		if(valid_numeric_chars.indexOf(value.charAt(i)) != -1)
		{
			if(dot_found == false || value.charAt(i) != ".")
			{
				newvalue += value.charAt(i);
			}
			
			if(value.charAt(i) == ".")
			{
				dot_found = true;
				non_numeric_found = true; 
			}
		}
		else if(i == 0 && value.charAt(i) == "-")
		{
			newvalue += value.charAt(i);
		}
		else
		{
			non_numeric_found = true;
		}
	}
	
	if(non_numeric_found)
	{
		textbox.value = newvalue;
	}
}

/********************************************************************
 * Date fields
 */

function format_date(textbox)
{
	 if(valid_field_colors[textbox] == null)
	 {
		 valid_field_colors[textbox]					= new Array();
		 valid_field_colors[textbox]['backgroundColor'] = textbox.style.backgroundColor;
		 valid_field_colors[textbox]['color']           = textbox.style.color;
	 }
	 
	value = textbox.value;

	if(value == null || value.length == 0)
	{
		return;
	}
	
	separator1 = '';
	separator2 = '';
	value1 = '';
	value2 = '';
	value3 = '';

	previous_value = '';
	typed_index = 0;
	var i;
	for(i = 0; i < value.length; i++)
	{
		current_char = value.charAt(i);
		
		if(i == 0 || (previous_value == ' ' && !isNaN(current_char)) || (current_char == ' ' && !isNaN(previous_value)) || (isNaN(current_char) && !isNaN(previous_value)) || (!isNaN(current_char) && isNaN(previous_value)))
		{
			//change from number to separator or from separator to number
			typed_index++;

			//manage the case of a value starting with a separator
			if(i == 0 && isNaN(current_char))
			{
				typed_index = 2;
			}
		}

		switch(typed_index)
		{
			case 1:
				value1 += '' + current_char;
				break;

			case 2:
				separator1 += '' + current_char;
				break;

			case 3:
				value2 += '' + current_char;
				break;

			case 4:
				separator2 += '' + current_char;
				break;

			case 5:
				value3 += '' + current_char;
				break;
		}

		previous_value = current_char;
	}

	/*
	 * Note: date_format is globally scoped
	 */
	
	date_value = get_formatted_date(value1, value2, value3, date_format);
	
	if(date_value != INVALID_DATE)
	{
		textbox.value = get_formatted_date(value1, value2, value3, date_format);
		textbox.style.backgroundColor = valid_field_colors[textbox]['backgroundColor'];
		textbox.style.color = valid_field_colors[textbox]['color'];
	}
	else
	{
		textbox.style.backgroundColor = INVALID_DATE_FIELD_COLOR;
		textbox.style.color           = INVALID_DATE_TEXT_COLOR;
	}
}

function get_formatted_date(value1, value2, value3, date_format)
{
	date_part1 = date_format.substring(0, 1);
	separator1 = date_format.substring(1, 2);
	date_part2 = date_format.substring(2, 3);
	separator2 = date_format.substring(3, 4);
	date_part3 = date_format.substring(4, 5);
	
	date_part_value1 = get_date_part_value(value1, date_part1);
	date_part_value2 = get_date_part_value(value2, date_part2);
	date_part_value3 = get_date_part_value(value3, date_part3);

	day = null;
	month = null;
	year = null;
	switch(date_part1)
	{
		case 'd':
			day = date_part_value1;
			break;
		case 'm':
			month = date_part_value1;
			break;
		case 'y':
			year = date_part_value1;
			break;
	}
	switch(date_part2)
	{
		case 'd':
			day = date_part_value2;
			break;
		case 'm':
			month = date_part_value2;
			break;
		case 'y':
			year = date_part_value2;
			break;
	}
	switch(date_part3)
	{
		case 'd':
			day = date_part_value3;
			break;
		case 'm':
			month = date_part_value3;
			break;
		case 'y':
			year = date_part_value3;
			break;
	}
	
	if(!check_date_validity(day, month, year))
	{
		return INVALID_DATE;
	}
	else
	{	
		return date_part_value1 + separator1 + date_part_value2 + separator2 + date_part_value3;
	}
}

function get_date_part_value(value, date_part)
{
	switch(date_part)
	{
		case 'd':
			return get_checked_day(value);
			break;
		case 'm':
			return get_checked_month(value);
			break;
		case 'y':
			return get_checked_year(value);
			break;
	}
}

function get_checked_day(value)
{
	day = null;
	if(value != null && value.length > 0 && !isNaN(value) && value >= 1 && value <= 31)
	{
		day = value;
	}
	else
	{
    	date = new Date();
    	day = date.getDate();
	}

	if(day < 10 && day.length != 2)
	{
		day = day * 1; //manage the case of leading zeros (e.g. '0007')
		day = '0' + day;
	}

	return day;
}

function get_checked_month(value)
{
	month = null;
	if(value != null && value.length > 0 && !isNaN(value) && value >= 1 && value <= 12)
	{
		month = value;
	}
	else
	{
    	date = new Date();
    	month = date.getMonth();
    	month = month + 1;
	}

	if(month < 10 && month.length != 2)
	{
		month = month * 1; //manage the case of leading zeros (e.g. '0007')
		month = '0' + month;
	}

	return month;
}

function get_checked_year(value)
{
	if(value != null && value.length > 0 && !isNaN(value))
	{
		complete_year = get_complete_year(value);

		current_date = new Date();
		current_year = current_date.getFullYear();
		year_diff = complete_year - current_year;

		if(year_diff > 15)
		{
			return get_complete_year(complete_year - 100);
		}
		else
		{
			return complete_year;
		}
	}
	else
	{
		date = new Date();
		year = date.getFullYear();
		return year;
	}
}

function get_complete_year(year)
{
	date = new Date(year, 1, 1);

	full_date = date.getFullYear();

	if(year.length < 4 && full_date < 2000)
	{
		full_date += 100;
	}
	
	return full_date;
}

function check_date_validity(day, month, year)
{
	//alert(year + "-" + month + "-" + day);
	if(month == 2 && day > 28)
	{
		return is_bissextile(year);
	}
	else
	{
		if(!isNaN(day) && day > 0 && day < 31)
		{
			return true;
		}
		else if(day == 31)
		{
			if(month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}

function is_bissextile (year)
{
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0));
}

function format_time(textbox, with_seconds)
{
	var value = $j(textbox).val();
	
	if(value.length == 0)
	{
		return;
	}
	else if(value.length == 3)
	{
		value = value.substring(0, 2) + ":" + value.substring(2, 3);
	}
	else if(value.length == 4)
	{
		value = value.substring(0, 2) + ":" + value.substring(2, 4);
	}
	else if(value.length == 6)
	{
		value = value.substring(0, 2) + ":" + value.substring(2, 4) + ":" + value.substring(4, 6);
	}
	
	var numbers = "0123456789";
	
	var hour = "";
	var min  = "";
	var sec  = "";
	var separators_found = 0;
	var last_is_separator = false;
	for(var i = 0; i < value.length; i++)
	{
		if(numbers.indexOf(value.charAt(i)) != -1)
		{
			last_is_separator = false;
			
			if(separators_found == 0)
			{
				hour += value.charAt(i);
			}
			else if (separators_found == 1)
			{
				min += value.charAt(i);
			}
			else if (separators_found == 2)
			{
				sec += value.charAt(i);
			}
		}
		else
		{
			if(last_is_separator == false)
			{
				separators_found++;
			}
			
			last_is_separator = true;
		}
	}
	
	newValue = "";
	
	if(hour.length > 0)
	{
		hour         = parseInt(hour, 10);
		var hour_str = hour + "";
		
		if(hour > 24)
		{
			hour     = hour % 24 + "";
			hour_str = hour + "";
		}
		
		if(hour < 10 && hour_str.length < 2)
		{
			hour = "0" + hour;
		}
	}
	else
	{
		hour = "00";
	}
	
	newValue += hour + ":"; 
	
	if(min.length > 0)
	{
		min         = parseInt(min, 10);
		var min_str = min + "";
		
		if(min > 60)
		{
			min     = min % 60;
			min_str = min + "";
		}
		
		if(min < 10 && min_str.length < 2)
		{
			min = "0" + min;
		}
	}
	else
	{
		min = "00";
	}
	
	newValue += min;
	
	if(with_seconds)
	{
		if(sec.length > 0)
		{
			if(sec > 60)
			{
				sec = sec % 60 + "";
			}
			
			if(sec < 10 && sec.length < 2)
			{
				sec = "0" + sec;
			}
		}
		else
		{
			sec = "00";
		}
		
		newValue += ":" + sec;
	}
	
	$j(textbox).val(newValue);
}

function update_datetime_hidden_field_from_date(element)
{
	var date_fieldname   = $j(element).attr("name");
	var time_fieldname   = date_fieldname.replace("__Date__", "__Time__");
	var hidden_fieldname = date_fieldname.replace("__Date__", "")
	
	var date = $j('[name="' + date_fieldname + '"]').val();
	var time = $j('[name="' + time_fieldname + '"]').val();
	
	if(time.length == 5)
	{
		time += ':00';
	}
	
	if(date.length == 0 || time.length == 0)
	{
		$j('[name="' + hidden_fieldname + '"]').val("");
	}
	else
	{
		$j('[name="' + hidden_fieldname + '"]').val(date + " " + time);
	}
}

function update_datetime_hidden_field_from_time(element)
{
	var time_fieldname   = $j(element).attr("name");
	var date_fieldname   = time_fieldname.replace("__Time__", "__Date__");
	var hidden_fieldname = time_fieldname.replace("__Time__", "")
	
	var date = $j('[name="' + date_fieldname + '"]').val();
	var time = $j('[name="' + time_fieldname + '"]').val();
	
	if(time.length == 5)
	{
		time += ':00';
	}
	
	if(date.length == 0 || time.length == 0)
	{
		$j('[name="' + hidden_fieldname + '"]').val("");
	}
	else
	{
		$j('[name="' + hidden_fieldname + '"]').val(date + " " + time);
	}
}

function zeroPad(num, places)
{
	if(typeof(num) != "undefined" && num != null)
	{
		var zero = places - num.toString().length + 1;
		return Array(+(zero > 0 && zero)).join("0") + num;
	}
	
	return null;
}

function set_row_color(checkbox)
{
	if(!$j(checkbox).parents("tr").hasClass("searchHeader"))
	{
		if($j(checkbox).attr("checked"))
		{
			$j(checkbox).parents("tr").addClass("selected_row");
		}
		else
		{
			$j(checkbox).parents("tr").removeClass("selected_row");
		}
	}
}

/********************************************************************
 * Start alaxos functions
 */
$j(document).ready(function(){
	
	start_alaxos();
	
});

function start_alaxos()
{
	$j(".inputDate").change(function(e){
		format_date(this);
	});
	
	$j(".inputDatetimeD").change(function(e){
		update_datetime_hidden_field_from_date(this);
	}).bind("keydown", function(e){
		if(e.which == 13){
			update_datetime_hidden_field_from_date(this);
		}
	}).bind("blur", function(e){
		//to force update of the hidden field AFTER the date has been formatted
		update_datetime_hidden_field_from_date(this);
	}).bind("keyup", function(e){
		update_datetime_hidden_field_from_date(this);
	});
	
	$j(".inputTimeHm").bind("keydown", function(e){
		if(e.which == 13){
			format_time(this, false);
			update_datetime_hidden_field_from_time(this);
		}
	}).bind("keyup", function(e){
		update_datetime_hidden_field_from_time(this);
	}).bind("blur", function(e){
		format_time(this, false);
		update_datetime_hidden_field_from_time(this);
	});
	
	$j(".inputTimeHms").bind("keydown", function(e){
		if(e.which == 13){
			format_time(this, true);
			update_datetime_hidden_field_from_time(this);
		}
	}).bind("keyup", function(e){
		update_datetime_hidden_field_from_time(this);
	}).bind("blur", function(e){
		format_time(this, true);
		update_datetime_hidden_field_from_time(this);
	});
	
	$j(".inputTHm").bind("keydown", function(e){
		if(e.which == 13){
			format_time(this, false);
		}
	}).bind("blur", function(e){
		format_time(this, false);
	});
	
	$j(".inputTHms").bind("keydown", function(e){
		if(e.which == 13){
			format_time(this, true);
		}
	}).bind("blur", function(e){
		format_time(this, true);
	});
	
	$j(".inputFloat").bind("keydown", function(e){
		if(e.which == 13){
			format_number(this, true);
		}
	}).bind("keyup", function(e){
			format_number(this, true);
		});
	
	$j(".inputInteger").bind("keydown", function(e){
		if(e.which == 13){
			format_number(this, false);
		}
	}).bind("keyup", function(e){
			format_number(this, false);
		});
	
	/*
	 * Force searching when 'enter' is pressed in a search field 
	 */
	$j("tr.searchHeader input").each(function(){
		$j(this).keypress(function(e){
			if(e.keyCode == 13)
			{
				search();
				e.preventDefault(); //for IE, to not submit any form around the search filters
			}
		});
	});
	
	$j("tr.searchHeader select").each(function(){
		$j(this).keypress(function(e){
			if(e.keyCode == 13)
			{
				search();
				e.preventDefault(); //for IE, to not submit any form around the search filters
			}
		});
	});
	
	/*
	* Add double click to administrations lists if a link with id="detail" exists in the row
	*/
	$j("table.administration > tbody > tr").each(function(i){
	
		if($j("a.to_detail", this).length > 0)
		{
			$j(this).bind("dblclick", function(){
		
				window.location = $j("a.to_detail", this).attr("href");
				
			});	
		}
	});
	
	/*
	 * Select all rows in admin lists
	 */
	$j("#_TechSelectAll").click(function(){
		
		$j("input.model_id[type=checkbox]").each(function(){
			
			$j(this).attr("checked", ($j("#_TechSelectAll:checked").length > 0));		
			
			set_row_color($j(this));
		});
		
	});
	
	/*
	 * Select row by clicking on it 
	 */
	$j("table.administration tr td").click(function(i){
		
		if(!$j(this).parents("tr").hasClass("searchHeader"))
		{
			var checkbox = $j(this).parent().find("td").find("input[type=checkbox]");
			if($j(checkbox).attr("checked"))
			{
				$j(checkbox).attr("checked", false);
			}
			else
			{
				$j(checkbox).attr("checked", true);
			}
	
			set_row_color(checkbox);
		}
	});
	
	/*
	 * Prevent conflict between click on row and click on checkbox in the row 
	 */
	$j("table.administration tr td input[type=checkbox]").click(function(e){
		e.stopPropagation();
		
		set_row_color($j(this));
	});
	
	/*
	* Register the submit for actionAll
	*/
	$j('#chooseActionFormBtn').click(function() {
	 
		/*
		 * Find all model ids
		 */
		if($j("input.model_id[type=checkbox]:checked").length > 0)
		{
			var action = $j('#ActionToPerform').val();
			
			var performAction = true;
			
			if(action.indexOf("deleteAll") != -1)
			{
				performAction = confirm(confirmDeleteAllText);
			}
			else if(action == "")
			{
				performAction = false;
				alert(pleaseChooseActionToPerformText);
			}
			
			if(performAction)
			{
				
				var url = $j("#_TechActionAllUrl").val();
				
				$j('#chooseActionFormBtn').after("<form id=\"actionAllForm\" action=\"" + url + "\" method=\"post\"></form>");
				
				$j("#actionAllForm").append("<input type=\"hidden\" name=\"data[_Tech][action]\" value=\"" + action + "\">");
				
				$j("input.model_id[type=checkbox]:checked").each(function(){
					
					$j("#actionAllForm").append("<input type=\"hidden\" name=\"" + $j(this).attr("name") + "\" value=\"" + $j(this).val() + "\">");
					
				});
				
				/*
				 * If the Security component is used, try to find a token to use
				 */
				if($j("input[name='data[_Token][key]']").length > 0)
				{
					$j("#actionAllForm").append("<input type=\"hidden\" name=\"data[_Token][key]\" value=\"" + $j("input[name='data[_Token][key]']").val() + "\" />");
				}
				
				$j("#actionAllForm").submit();
			}
		}
		else
		{
			alert(pleaseSelectAtLeastOneItem);
		}
	});
}