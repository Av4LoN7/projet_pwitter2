<?php

abstract class coreController
{
    private   $_viewObject;
    protected $_user_id;
    protected $_controller;
    protected $_action;
    protected $_response;
    protected $_authModule;

    public function __construct($request)
    {
        $this->_user_id = ctype_digit($request->get("userID")) ? (int) $request->get("userID") : 0;
        $this->_controller = $request->get("controller")."Controller";
        $this->_action = $request->get("action")."Action";

        if(method_exists($this, '__preload')){
            $this->__preload();
        }
    }

    public function __preload()
    {
        $this->_authModule = new authModule();
    }

    public function renderP($view, $data = null)
    {
        $this->_viewObject = new view($view);

        if($view != null)
        {
            if( $viewRender = $this->_viewObject->renderPartial($data))
            {
                return $viewRender;
            }
            else
            {
                $this->_response = include_once('404.php');
            }
        }
    }

    public function renderFinale($layout, $data = [])
    {

        $this->_viewObject = new view($layout);

        if($layout && $data !== null)
        {
            $this->_response = $this->_viewObject->renderPartial($data);
        }
        else
        {
            $this->_response = include_once('404.php');
        }
    }

    public function indexAction()
    {
        $this->_response = include_once('App/Views/acceuil.php');
    }

    public function getResponse()
    {
        return $this->_response;

    }

}

?>