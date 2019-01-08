<?php
class SRequest{
	private $_get;
	private $_post;
	private static $_instance;

    /**
     * singleton sRequest catch post and get
     * SRequest constructor.
     */
	private function __construct(){
		$this->_get = $_GET;
		$this->_post = $_POST;
		$_GET = $_POST = null;
	}

	public function get($var = null){
		return $this->getVar($this->_get,$var);
	} 
	public function post($var = null){
		return $this->getVar($this->_post,$var);
	} 

	private function getVar($req,$var = null){
		if(isset($var)){
			if(isset($req[$var])){
				$result = $req[$var];
			}else{
				$result = null;
			}
		}else{
			$result = $req;
		}
		return $result;
	}

    /**
     * auto instanciation srequest class
     * @return SRequest
     */
	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new SRequest();
		}
		return self::$_instance;
	}
}