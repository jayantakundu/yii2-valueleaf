<?php
/**http://alerts.valueleaf.com/api/web2sms.php?workingkey="xxxx"&sender="xxx"&to="xxxx"&message="xxxxxxxx";
*/
namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Simple Application Component that allows you to send SMS using valueleaf Gateway easily.
 * 
 */

class Sms extends component {
    
    private $https = false;
    private $_workingkey = 'valueleaf workingkey';
    private $_sender = 'valueleaf sender';
    private $_parameters = array();

    const SERVICE_URI = 'http://alerts.valueleaf.com';
    private static $REQUIRED_PARAMETERS = array('to','message');
    

    function __construct() {
       
    }

    
    public function setParameter($key, $value) {
        $this->_parameters[$key] = $value;
    }

    public function getParameter($key, $defvalue = false)  {
        if(isset($this->_parameters[$key])) {
            return $this->_parameters[$key];
        }
        return $defvalue;
    }

    public function getServiceURL($type = false) {      
        if($type) {
            switch(strtoupper($type)) {

                case 'AUTH': return  self::SERVICE_URI; 
                case 'SEND': return  self::SERVICE_URI . '/api/web2sms.php?';
            }
        }
        return false;
    }

    protected function prepareParameters() {
    	//$params = array('username' => $this->_username, 'pass' => $this->_password);
        $params = array('workingkey' => $this->_workingkey, 'sender' => $this->_sender);
        foreach (self::$REQUIRED_PARAMETERS as $key) {
            $params[$key] = $this->getParameter($key);
        }
        
        return $params;
    }


    public function send($config) {

    	foreach ($config as $key => $value) {
    		if (is_array($value)){
            	$value = implode(',', $value);
            }
            if($key=='message'){
            	$value = urlencode($value);
            }
           
            $this->setParameter($key, $value);
        }
    	
        $params = $this->prepareParameters();
        $serviceURL = $this->getServiceURL('SEND');
        $postData = '';
        foreach ($params as $name => $value) {
            $postData .= '&' . $name . '=' . $value;
        }
        $postData = substr($postData, 1);
        
        $request = curl_init();
        curl_setopt($request, CURLOPT_POST, count($params));
        curl_setopt($request, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        if ($this->https === true) {
            curl_setopt($request, CURLOPT_URL, $serviceURL);
            curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($request, CURLOPT_URL, $serviceURL);
        }
        $response = curl_exec($request);
        
        $pos = strpos($response, 'GID');
        // if the request fails
        
        if ($pos === false) {
            // fails log here
        }
        
        
    }


    protected function getSession() {
        $serviceURL = $this->getServiceURL('AUTH');
        return true;
    }

}
