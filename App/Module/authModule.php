<?php

class authModule
{
    private $_login;
    private $_rang;

    public function __construct(){
        if(!isset($_SESSION)){
            session_start();
        }
        if(isset($_SESSION['currentUser']['auth'])){
            $this->_login = $_SESSION['currentUser']['auth'];
            $this->_rang = $_SESSION['currentUser']['rang'];
        }
    }
    /**
     * get user login ID
     * @return mixed
     */
    public function getLogin()
    {
        return $this->_login;
    }
    /**
     * set login var after user connect process
     * @param $login
     */
    public function connect($login){

        $this->_login = (int)$login;
    }
    /**
     * unset login var after disconnect process
     */
    public function disconnect(){
        $this->_login = null;
    }
    /**
     * user is logged validation method
     * @return bool
     */
    public function isConnected(){
        return !is_null($this->_login);
    }
    /**
     * set user rank in authmodule
     * @param $rang
     */
    public function setRang($rang)
    {
        $this->_rang = (int) $rang;
    }
    /**
     * get user rank from authmodule
     * @return mixed
     */
    public function getRang()
    {
        return $this->_rang;
    }
    /**
     * assigante user id and rank in session before destruct class
     */
    public function __destruct(){
        $_SESSION['currentUser']['auth'] = $this->_login;
        $_SESSION['currentUser']['rang'] = $this->_rang;
    }
}