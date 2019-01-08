<?php

class authController extends coreController
{
    private $_authModel;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->_authModel = new authModels();
    }

    /**
     * connect user method
     * @param $request
     */
    public function connectAction($request)
    {
        $ident = htmlspecialchars($request->post('email'));
        $pass = htmlspecialchars($request->post('password'));
        $response = $this->_authModel->connectUserAction($ident, $pass);

        if( is_string($response))
        {
            $this->_response = $this->renderP('acceuil', compact('response'));
        }
        elseif(is_array($response))
        {
            $this->_authModule->connect($response['id_utilisateur']);
            $this->_authModule->setRang($response['id_role']);
            header('location:-index.php?controller=frontProfil&action=frontPage&userID='.$response['id_utilisateur']);
            exit();
        }
        else
        {
            $response["error"] = "une erreur s'est produite veuillez nous en excusez";
            $this->_response = $this->renderP('acceuil', compact('response'));
        }

       echo $this->getResponse();
    }

    /**
     * disconnect user method
     * @param $request
     */
    public function disconnectAction($request)
    {
        //var_dump($request);
        if($this->_authModule->getLogin() == intval($request->get("userID")))
        {
            //echo "ok";
            if($this->_authModel->onlineOff($this->_authModule->getLogin()))
            {
                //echo "ok";
                $this->_authModule->disconnect();

                session_destroy();
                header('location:'. $_SERVER['PHP_SELF']);
                exit();
            }
        }
        else
        {
            $error = "Vous n'avez pas les droit nécessaire pour cette action";
            $this->renderP('index', 'error');
        }
        echo $this->getResponse();
    }
}