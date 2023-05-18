<?php
App::uses('Component', 'Controller');

class SMSComponent extends Component {
	
  private $username = "madaner";
  private $password = "madaner12345";
  private $client   = null;
  
 
	var $ERROR_LIST = array(
		1 => "نام کاربری یا رمز عبور صحیح نیست",
		2 => "اعتبار حساب کافی نیست",
		3 => "حساب کاربر فعال نیست",
		4 => "شماره گیرنده خالی است",
		5 => "شماره گیرنده معتبر نیست",
		6 => "شماره فرستنده معتبر نیست",
		7 => "هیچ شماره ای به حساب شما اختصاص نیافته است",
		8 => "متن پیام خالی است", 
		9 => "متن پیام طولانی است",
		10=>"خطا در سرویس دهنده",
		11=>"خطا در برقراری ارتباط با سوئیچ مخابرات",
		12=>"شناسه پیام نامعتبر است",
		13=>"آدرس انتقال ترافیک معتبر نیست",
		14=>"رمز عبور خالی است",
		15=>"شما حق دسترسی به این ماژول را ندارید",
		16=>"این ماژول در حال حاضر قابل استفاده نیست",
		17=>"این ماژول برای شما غیرفعال شده است",
		18=>"مهلت استفاده از این ماژول خاتمه یافته است",
		19=>"مهلت استفاده از این شماره خاتمه یافته است",
		20=>"این شماره برای شما غیرفعال شده است"
	);

	var $DELIVERY_STATUS = array(
		0 => "ارسال شده به مخابرات" ,
		1 => "رسیده به گوشی" ,
		2 => "نرسیده به گوشی" ,
		4 => "در صف ارسال" ,
		8 => "رسیده به مخابرات" ,
		16 => "نرسیده به مخابرات" ,
	); 

	/*private function construct(){
		
		App::import('Vendor','nusoap', array('file'=>'nusoap'.DS.'lib'.DS.'nusoap.php'));
        $this->client = new nusoap_client("http://azinpanel.ir/class/sms/webservice/server.php?wsdl");                                   
        $this->client->soap_defencoding = 'UTF-8';
        $this->client->decode_utf8 = true;
        $this->client->setCredentials($this->username, $this->password, "basic");
        
        echo "mojtaba";
	}*/
	/**
	* 
	* @param undefined $message
	* @param undefined $from
	* @param undefined $to
	* @param undefined $type
	* 
*/	
	public function SendSMS($message/*, $from*/, $to, $type)
	{
		if(is_array($to))
		{
			$i = sizeOf($to);
			
			while($i--)
			{
				$to[$i] =  self::CorrectNumber($to[$i]);
			}
		}
		else
		{
			$to = self::CorrectNumber($to);
		}

		$params = array(
			'from'		=> 500020444 , //$from,
			'rcpt_array'=> $to,
			'msg'		=> $message,
			'type'		=> $type
		);

        $response = $this->call("enqueue", $params);

		return $response;
    }
	/**
	* 
	* 
*/
    public function GetUserBalance()
	{
		$response = $this->call("GetCredit", array());
        if($response['state']=='error'){
           // set log 
				Controller::loadModel('Errorlog');
				$this->Errorlog->get_log('SMS Component GetUserBalance',$response['message']);
		  // set log 
        }
        
			
		return $response;
    }
/**
* 
* @param undefined $method
* @param undefined $params
* 
*/
    private function call($method, $params)
	{
       
        App::import('Vendor','nusoap', array('file'=>'nusoap'.DS.'lib'.DS.'nusoap.php'));
        $this->client   = new nusoap_client("http://azinpanel.ir/class/sms/webservice/server.php?wsdl");
        $this->client->soap_defencoding = 'UTF-8';
        $this->client->decode_utf8 = true;
       /* // set log 
		Controller::loadModel('Errorlog');
		$this->Errorlog->get_log('SMS Component user/pass ', 'user='.$this->username.' pass='.$this->password);
        // set log*/
        $this->client->setCredentials($this->username, $this->password, "basic");
        
       
        $result = $this->client->call($method, $params);

			if($this->client->fault || ((bool)$this->client->getError()))
			{
				// set log 
				Controller::loadModel('Errorlog');
				$this->Errorlog->get_log('SMS Component call',$this->client->getError());
		        // set log 
                return array('error' => true, 'fault' => true, 'message' => $this->client->getError());
			}

        return $result;
    }
/**
* 
* @param undefined $uNumber
* 
*/
	public static function CorrectNumber(&$uNumber)
	{
		$uNumber = Trim($uNumber);
		$ret = &$uNumber;
		
		if (substr($uNumber,0, 3) == '%2B')
		{ 
			$ret = substr($uNumber, 3);
			$uNumber = $ret;
		}
		
		if (substr($uNumber,0, 3) == '%2b')
		{ 
			$ret = substr($uNumber, 3);
			$uNumber = $ret;
		}
		
		if (substr($uNumber,0, 4) == '0098')
		{ 
			$ret = substr($uNumber, 4);
			$uNumber = $ret;
		}
		
		if (substr($uNumber,0, 3) == '098')
		{ 
			$ret = substr($uNumber, 3);
			$uNumber = $ret;
		}
		
		
		if (substr($uNumber,0, 3) == '+98')
		{ 
			$ret = substr($uNumber, 3);
			$uNumber = $ret;
		}
		
		if (substr($uNumber,0, 2) == '98')
		{ 
			$ret = substr($uNumber, 2);
			$uNumber = $ret;
		}
		
		if(substr($uNumber,0, 1) == '0')
		{ 
			$ret = substr($uNumber, 1);
			$uNumber = $ret;
		}  
		   
		return '+98' . $ret;
	}

/**
* 
* @param undefined $number
* 
*/	
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




}
?>