<?php

class routerController extends coreController {

    /**
     * start action method
     * @param $request
     */
    public function startAction($request)
    {
            if( $this->_action != 'Action')
            {
                $action = $this->_action;
            }
            if($this->_controller != 'Controller' && $this->_controller != null)
            {
                $class = new $this->_controller($request);

                if(method_exists($class, $this->_action))
                {
                    $class->$action($request);
                }
            }
            else
            {
                $this->indexAction();
            }
    }

}