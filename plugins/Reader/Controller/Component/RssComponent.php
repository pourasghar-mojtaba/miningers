<?php
App::uses('Component', 'Controller');
App::uses('Xml', 'Utility');
class RssComponent extends Component {
	
   /**
     * Reads an (external) RSS feed and returns it's items.
     *
     * @param $feed - The URL to the feed.
     * @param int $items - The amount of items to read.
     * @return array
     * @throws InternalErrorException
     */
    public function read($feed, $items = 5) {
        try {
            // Try to read the given RSS feed
            $xmlObject = Xml::build($feed);
        } catch (XmlException $e) {
            // Reading XML failed, throw InternalErrorException
            throw new InternalErrorException();
        }

        $output = array();

        for($i = 0;$i < $items;$i++):
            if(is_object($xmlObject->channel->item->$i)) {
                $output[] = $xmlObject->channel->item->$i;
            }
        endfor;

        return $output;
    } 
    /**
    * 
    * @param undefined $feed
    * @param undefined $items
    * @param undefined $tags
    * 
*/
    public function readwithtag($feed, $items = 5,$tags=array()) {
        try {
            // Try to read the given RSS feed
            $xmlObject = Xml::build($feed);
        } catch (XmlException $e) {
            // Reading XML failed, throw InternalErrorException
            throw new InternalErrorException();
        }

        $output = array();
        
        if(!empty($tags)){
            $tags= explode('#',$tags);
        }
        $i=0;
        foreach($xmlObject->channel->item as $obj){
            if(is_object($obj)) {
                if(!empty($tags)){
                    foreach($tags as $tag)
                    {
                          $pos = strpos($obj->title, ' '.$tag.' ');
                          if ($pos !== false){
                              $output[] = $obj;
                              $i++;
                              break;
                          }                          
                    }             
                }  
            }
            
            if($items != -1)
				if ($i==$items) break;
        };

        return $output;
    } 
	
	
 public function create_hashtag($tag,$str){
  	  $output = array();
	  if(!empty($tag)){
            $tags= explode('#',$tag);
        }
	  if(!empty($tags)){
	  	$tags = array_unique($tags);
        foreach($tags as $tag)
        {
			$pos = strpos($str, ' '.$tag.' ');
              if ($pos !== false){ 
				  $str = str_replace($tag ,'#'.$tag,$str);				
                  continue;
              } 
		}
	  }	
	  return $str;	
  }	
	
 /**
 * 
 * @param undefined $document
 * 
*/   
  public function html2txt($document){
   /* $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                   '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA*/
    $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                   '@<![\s\S]*?--[ \t\r]*>@'         // Strip multi-line comments including CDATA
    );
    $text = preg_replace($search, '', $document);
    return $text;
  }  
  public function clear_news($str){
      $str=str_replace('\n', '', $str);
      $str=str_replace('\r', '', $str);
      $str=str_replace("\r\n", "", $str);
      $str=str_replace("\n \n", "", $str);
      return $str;
  }

}
?>