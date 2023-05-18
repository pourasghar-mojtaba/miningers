<?php
	App::uses('HtmlHelper', 'View/Helper');
	
	class GilaceHelper extends AppHelper {
		
		public $helpers = array('Html','Session');
	 /**
	 * 
	 * @param undefined $content
	 * 
*/
	 public  function filter_editor($content)
		{
		  $content = str_replace("&lt;", "<", $content);
		  $content = str_replace("&gt;", ">", $content);
		  $content = str_replace("&amp;", "&", $content);
		  $content = str_replace("&nbsp;", " ", $content);
		  $content = str_replace("&quot;", "\"", $content);	
		  $content = str_replace("\\n", "<br>", $content);
		  $content=$this->convert_tag_to_link($content);
		  $content=$this->convert_username_to_link($content);
		  $content=$this->gifbb2html($content);
		  return $content;
		}
		
	 public  function convert_character_editor($content)
		{
		  $content = str_replace("&lt;", "<", $content);
		  $content = str_replace("&gt;", ">", $content);
		  $content = str_replace("&amp;", "&", $content);
		  $content = str_replace("&nbsp;", " ", $content);
		  $content = str_replace("&quot;", "\"", $content);	
		  $content = str_replace("\\n", "", $content);
		  return $content;
		}	
		
	/**
	* 
	* @param undefined $formats
	* @param undefined $time
	* 
*/	
	public function show_persian_date($format="Y/m/d - H:i",$time = 1)
	{
		App::import('Vendor', 'jdf');
		$timezone = 0;//برای 3:30 عدد 12600 و برای 4:30 عدد 16200 را تنظیم کنید
		$now = date("Y-m-d", $time + $timezone);
		$time = date("H:i:s", $time + $timezone);
		list($year, $month, $day) = explode('-', $now);
		list($hour, $minute, $second) = explode(':', $time);
		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		$jalali_date = jdate($format,$timestamp);
		return $jalali_date;
	}
/**
* 
* 
*/
public function get_current_persian_date()
{
	App::import('Vendor', 'jdate');
	$date = new jdate();
	return $date->show_date("l d F Y " , time());
	//return jdate("l d F Y ",$time);
}	
 /**
 * 
 * @param undefined $text
 * @param undefined $seprator
 * 
*/
	public function get_tag($text,$seprator='#')
	{
        preg_match_all('/(?!\b)('.$seprator.'\w+\b)/u',$text,$matches);
		return $matches[1];
	}
	
	public function get_username_tag($text,$seprator='@')
	{
		
		$keyword =array();
		if(isset($text)){
			preg_match_all('/'.$seprator.'([\p{L}\p{Mn}\_0-9A-z]+)/u',$text,$matches) ;
			
			if(!empty($matches)){
				foreach($matches[1] as $tag)
				{
					if(preg_match('/^[a-z][a-z\d\_]{2,62}[a-z\d]$/i',$tag))
					{
						$keyword[]=$tag;
					}
				}
			}
		}
		return $keyword;
	}
	
	/**
	* 
	* @param undefined $text
	* 
*/
public function convert_tag_to_link($text)
{
	$tags=$this->get_tag($text,'#');
   // print_r($tags);
	if(isset($tags) && !empty($tags)){
		foreach($tags as $tag)
		{
			$taglink=str_replace('#','',$tag);
            $text = str_replace($tag,"<a href='".__SITE_URL."posts/tags/".$taglink."'>".$tag."</a>", $text);
             
		}
	}
	return $text;
}
/**
* 
* @param undefined $text
* 
*/
public function convert_username_to_link($text)
{
	$tags=$this->get_username_tag($text);

	if(isset($tags) && !empty($tags)){
		foreach($tags as $tag)
		{
		$text = str_replace('@'.$tag,"@<a href='".__SITE_URL.$tag."'>".$tag."</a>", $text);
		}
	}
	return $text;
}

/**
* 
* @param undefined $url
* 
*/
public function filter_url($url)
{
	$new_url=parse_url($url, PHP_URL_HOST);
	if(strlen($url)>strlen($new_url)+10){
		$new_url.='/... ';
	}
	return $new_url;
}

function farsidigit($in_num){
    $in_num = str_replace('0' , '٠' , $in_num);
    $in_num = str_replace('1' , '١' , $in_num);
    $in_num = str_replace('2' , '٢' , $in_num);
    $in_num = str_replace('3' , '٣' , $in_num);
    $in_num = str_replace('4' , '۴' , $in_num);
    $in_num = str_replace('5' , '۵' , $in_num);
    $in_num = str_replace('6' , '۶' , $in_num);
    $in_num = str_replace('7' , '٧' , $in_num);
    $in_num = str_replace('8' , '٨' , $in_num);
    $in_num = str_replace('9' , '٩' , $in_num);
return $in_num;
}

public  function change_to_validnum($number)
	{
		$number = Trim($number);
	
		if (substr($number,0, 3) == '%2B')
		{ 
			$ret = substr($number,0, 3);
            $number=str_replace($ret,'0',$number);
			return $number;
		}
		 
		if (substr($number,0, 3) == '%2b')
		{ 
			$ret = substr($number,0, 3);
			$number=str_replace($ret,'0',$number);
			return $number;
		}
		
		if (substr($number,0, 4) == '0098')
		{ 
			$ret = substr($number,0, 4);
			$number=str_replace($ret,'0',$number);
			return $number;
		}
		
		if (substr($number,0, 3) == '098')
		{ 
			$ret = substr($number,0, 3);
			$number=str_replace($ret,'0',$number);
			return $number;
		}
		
		
		if (substr($number,0, 3) == '+98')
		{ 
			$ret = substr($number,0, 3);
			$number=str_replace($ret,'0',$number);
			return $number;
		}
		
		if (substr($number,0, 2) == '98')
		{ 
			$ret = substr($number,0, 2);
			$number=str_replace($ret,'0',$number);
			return $number;
		}
		
		if(substr($number,0, 1) == '0')
		{ 
			$ret = substr($number,0, 1);
			$number=str_replace($ret,'0',$number);
			return $number;
		}  
		   
		return $number;
	}	


/**
* 
* @param undefined $text
* 
*/
function bb2html($text)
{
  $bbcode = array(
                ":angel:",
				":@",
				":D",
				":blush:",
				":s",
				":cool:",
				":dodgy:",
				":exclamation:",
				":heart:",
				":huh:",
				":idea:",
				":rolleyes:",
				":(",
				":shy:",
				":sleepy:",
				":)",
				":P",
				":-/",
				";)", 
                "<", ">",
                "[list]", "[*]", "[/list]", 
                "[img]", "[/img]", 
                "[b]", "[/b]", 
                "[u]", "[/u]", 
                "[i]", "[/i]",
                '[color="', "[/color]",
                "[size=\"", "[/size]",
                '[url="', "[/url]",
                "[mail=\"", "[/mail]",
                "[code]", "[/code]",
                "[quote]", "[/quote]",
                '"]'
				);
  $htmlcode = array(
                
				"<img src='".__SITE_URL."/img/icons/smilies/angel.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/angry.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/biggrin.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/blush.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/confused.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/cool.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/dodgy.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/exclamation.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/heart.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/huh.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/lightbulb.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/rolleyes.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/sad.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/shy.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/sleepy.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/smile.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/tongue.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/undecided.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/wink.gif'  />",
                "&lt;", "&gt;",
                "<ul>", "<li>", "</ul>", 
                "<img src=\"", "\">", 
                "<b>", "</b>", 
                "<u>", "</u>", 
                "<i>", "</i>",
                "<span style=\"color:", "</span>",
                "<span style=\"font-size:", "</span>",
                '<a href="', "</a>",
                "<a href=\"mailto:", "</a>",
                "<code>", "</code>",
                "<table width=100% bgcolor=lightgray><tr><td bgcolor=white>", "</td></tr></table>",
                '">'
				);
  $newtext = str_replace($bbcode, $htmlcode, $text);
  $newtext = nl2br($newtext);//second pass
  $content = str_replace("&lt;", "<", $newtext);
  $content = str_replace("&gt;", ">", $content);
  return $content;
}

function gifbb2html($text)
{
  $bbcode = array(
                ":)",
                ":(",
                ";)",
                ":D",
                ";;)",
                ">:D<",
                ":-/",
                ":x",
                ':">',
                ":P",
                ":-*",
                "=((",
                ":-O",
                "X(",
                ":>",
                "B-)",
                ":-S",
                "#:-S",
                ">:)",
                ":((",
                ":))",
                ":|",
                "/:)",
                "=))",
                "O:-)",
                ":-B",
                "=;",
                ":-c",
                ":)]",
                "~X(",
                ":-h",
                ":-t",
                "8->",
                "I-)",
                "8-|",
                "L-)",
                ":-&",
                ":-$",
                "[-(",
                ":O)",
                "8-}",
                "<:-P",
                "(:|",
                "=P~",
                ":-?",
                "#-o",
                "=D>",
                ":-SS",
                "@-)",
                ":^o",
                ":-w",
                ":-<",
                ">:P",
                "<):)",
                "X_X",
                ":!!",
                "\m/",
                ":-q",
                ":-bd",
                "^#(^",
                ":ar!"


				);
  $htmlcode = array(           
				"<img src='".__SITE_URL."/img/icons/smilies/1.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/2.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/3.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/4.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/5.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/6.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/7.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/8.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/9.gif'  />",
				"<img src='".__SITE_URL."/img/icons/smilies/10.gif'  />",
              
                "<img src='".__SITE_URL."/img/icons/smilies/11.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/12.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/13.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/14.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/15.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/16.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/17.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/18.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/19.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/20.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/21.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/22.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/23.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/24.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/25.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/26.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/27.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/28.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/29.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/30.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/31.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/32.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/33.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/34.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/35.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/36.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/37.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/38.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/39.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/40.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/41.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/42.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/43.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/44.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/45.gif'  />",
                
                "<img src='".__SITE_URL."/img/icons/smilies/46.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/47.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/48.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/100.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/101.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/102.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/103.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/104.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/105.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/109.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/110.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/111.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/112.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/113.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/114.gif'  />",
                "<img src='".__SITE_URL."/img/icons/smilies/pirate_2.gif'  />"
				);
  $newtext = str_replace($bbcode, $htmlcode, $text);
  $newtext = nl2br($newtext);//second pass
  $content = str_replace("&lt;", "<", $newtext);
  $content = str_replace("&gt;", ">", $content);
  return $content;
}




/**
* 
* @param undefined $inputFile
* @param undefined $outputFile
* 
*/
function autoCompileLess($inputFile, $outputFile) {
  
  App::import('Vendor', 'less/lessc');
  
  // load the cache
  $cacheFile = $inputFile.".cache";

  if (file_exists($cacheFile)) {
    $cache = unserialize(file_get_contents($cacheFile));
  } else {
    $cache = $inputFile;
  }
  
  $less = new lessc;
  $less->setFormatter("compressed");
  $newCache = $less->cachedCompile($cache);
  
  if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
    file_put_contents($cacheFile, serialize($newCache));
    file_put_contents($outputFile, $newCache['compiled']);
  }
   
}


public function user_image($image,$sex,$alt='',$id ='image_img',$height=160,$width=160){						
   $user_image='';
    if(fileExistsInPath(__USER_IMAGE_PATH.$image )&& $image!='' ) 
	{
		$user_image = $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$image,array('width'=>$width,'height'=>$height,'alt'=>$alt,'id'=>$id));
	}
	else{		 
         if($sex==0)
		  $user_image = $this->Html->image('profile_women.png',array('width'=>$width,'height'=>$height,'alt'=>$alt,'id'=>$id)); 
		 elseif($sex==1)
		  $user_image = $this->Html->image('profile_men.png',array('width'=>$width,'height'=>$height,'alt'=>$alt,'id'=>$id)); 
		 elseif($sex==2) $user_image = $this->Html->image('company.png',array('width'=>$width,'height'=>$height,'alt'=>$alt,'id'=>$id)); 
        
	}
      
  return $user_image;  
}
	   

}






?>