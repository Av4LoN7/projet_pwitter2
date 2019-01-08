<?php

class commentaireController extends coreController
{
    use imageFormat; // contain image process and create thumb

    private $commModel;
    private $_idPwit;
    private $_userID;
    private $_idCom;
    private $_pwitModel;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->_idPwit = ctype_digit($request->post('pwitID')) ? (int) $request->post('pwitID') : 0;
        $this->_userID = ctype_digit($request->post('userID')) ? (int) $request->post('userID') : 0;
        $this->_idCom = ctype_digit($request->get('comID')) ? (int) $request->get('comID') : 0;
        $this->commModel = new commentaireModels();
    }

    /**
     * add commentaire on pwit method + atach image on com + create thumb for it
     * @param $request
     * @return int|string
     */
    public function addCommentaireAction($request)
    {
        if($this->_authModule->isConnected())
        {
            if($this->_userID == $this->_authModule->getLogin())
            {
                $source = $request->post("from") != null ? htmlspecialchars($request->post("from")) : null;
                $content = is_string($request->post("data")) && $request->post("data") != null ? htmlspecialchars($request->post("data")) : null;

                // if an image file is send
                if($_FILES['file_img']['name'] != null  && $source != null)
                {
                    $path = $this->addImageAction($this->_userID, $source);
                    if(isset($path['error']))
                    {
                        echo json_encode($path);
                    }
                    else
                    {
                        $extension = explode(".",$path);
                    }
                }

                // if com is succefully save in db, get its id
                if( $result = $this->commModel->addComm( $this->_idPwit, $this->_userID , $content, $path) )
                {
                    // make thumb of imgae file if exist
                    if(isset($path) && $path != null)
                    {
                        $thumb_path = "comm_image/user_miniature_".$this->_userID ."/".$result."comm.".end($extension);
                        $this->make_thumb($path,$thumb_path,200);
                    }
                    // get the lat inserted com
                    if($response = $this->commModel->getLastComm($result))
                    {
                        if($response[0]['c_img'] != null)
                        {
                            $response[0]['miniature'] = $this->makeThumbSrc($this->_userID,"comm", $response[0]['c_img'],$response[0]['id_commentaire']);
                        }

                        echo json_encode($response);
                    }
                }
                else
                {
                    $error['error'] = "Une erreur est survenue, votre commentaire n'a pas été enregistrer";
                    echo json_encode($error);
                    return false;
                }
            }
            else
            {
                $error['error'] = "Désolé, vous n'etez pas autorisez a effectuer cette action";
                echo json_encode($error);
                return false;
            }
        }
        else
        {
            $error['error'] = "Désolé, vous devez etre connecter pour effectuer cette action";
            echo json_encode($error);
            return false;
        }
    }

    /**
     * delete com on pwit process verification
     * @param $request
     * @return json
     */
    public function deleteComAction($request)
    {
       $pwitID = ctype_digit( $request->get("pwitID")) ? (int) $request->get("pwitID") : 0;

        $this->_pwitModel = new pwittModels();

        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() && $this->_idCom && $pwitID !=0)
            {
                // if his owner of the com
                if( $comOwner = $this->commModel->isOwner($this->_idCom, $this->_authModule->getLogin()))
                {
                    if($this->deleteCom($this->_idCom))
                    {
                        $success['success'] = "commentaire supprimer avec succéss";

                        echo json_encode($success);
                    }
                }
                // if his owner of the publication
                elseif($pwitOwner = $this->_pwitModel->isOwner($pwitID, $this->_authModule->getLogin()))
                {
                    if($this->deleteCom($this->_idCom))
                    {
                        $success['success'] = "commentaire supprimer avec succéss";

                        echo json_encode($success);
                    }
                }
                else
                {
                    $error['error'] = "vous ne pouvez pas effectuer cette operation";
                    echo json_encode($error);
                }
            }
            else
            {
                $error['error'] = "vous ne pouvez pas effectuer cette operation";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "vous devez être connecter pour effectuer cette operation";
            echo json_encode($error);
        }
    }

    /**
     * delete com on pwit method
     * @param $idCom
     * @return bool
     */
    private function deleteCom($idCom)
    {
        if( $comDelete = $this->commModel->deleteCom($idCom))
        {
            return true;
        }
        else
        {
            return false;
        }

    }
}