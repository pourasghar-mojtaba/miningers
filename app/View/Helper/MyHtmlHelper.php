<?php
	App::uses('HtmlHelper', 'View/Helper');
	class MyHtmlHelper extends HtmlHelper {
	    /**
		* 
		* @param undefined $url
		* @param undefined $full
		* 
*/
		public function url($url = null, $full = false) {
	        if(!isset($url['language']) && isset($this->params['language'])) {
	          $url['language'] = $this->params['language'];
	        }
	        return parent::url($url, $full);
	   }
	   
	   /**
	   * 
	   * 
*/
	   public function random_background() {
	        $image_name=rand(1,14);
			return $this->Html->image('/img/background/'.$image_name.'.jpg');
	   }
	   
	}
?>