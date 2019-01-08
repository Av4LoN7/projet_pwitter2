<?php
/**
 * connect to bdd and return pdo instance inside a static variable
 * Class SPDO
 */
class SPDO {
	private static $_instance;
	private $pdo;

    /**
     * initialaze connexion to bdd. BDD info inside ini.php
     * SPDO constructor.
     */
	private function __construct() {
		$this->pdo = new PDO(DB_DSN, DB_LOGIN ,DB_PWD);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new SPDO();
		}
		return self::$_instance;
	}
    /**
     * get the pdo connexion
     * @return PDO
     */
	public function getPDO(){
		return $this->pdo;
	}
}
?>