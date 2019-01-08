<?php

class categorieController extends coreController
{
    private $_catModel;
    private $_idTags;
    private $_idPwit;
    private $_idCat;


    public function __construct($request)
    {
        parent::__construct($request);
        $this->_catModel = new categorieModels();
        $this->_idCat = ctype_digit($request->get("idCat")) ? (int) $request->get("idCat") : 0;
        $this->_idTags = $request->get('tags') != null ? htmlspecialchars($request->get('tags')) : null;
        $this->_idPwit = ctype_digit($request->get('pwitt')) ? (int) $request->get('pwitt') : null;
    }

    /**
     * remove tags on pwit method
     * @param pass in the __construct
     */
    public function removeTagAction()
    {
        if($this->_authModule->isConnected())
        {
            if( $this->_idTags && $this->_idPwit != null)
            {
                if(( $response = $this->_catModel->removeCat( $this->_idTags, $this->_idPwit )) != false)
                {
                    $result['success'] = "tags retiré";
                    echo json_encode($result);
                }
                else
                {
                    $result['error'] = "Une erreur est survenue, veuillez recommencer";
                    echo json_encode($result);
                }
            }
            else
            {
                $result['error'] = "Une erreur est survenue, veuillez recommencer";
                echo json_encode($result);
            }
        }
        else
        {
            $result['error'] = "Vous devez être connecté pour effectuer cette action";
            echo json_encode($result);
        }
    }

    /**
     * addTags after pwit creation method
     *  @param pass in the __construct
     */
    public function addTagsAfterAction()
    {
        if($this->_authModule->isConnected())
        {
            if($this->_idTags && $this->_idPwit != null)
            {
                if( $response = $this->_catModel->addCat($this->_idTags, $this->_idPwit) )
                {
                    $result['success'] = "tags Ajouter";
                    echo json_encode($result);
                }
                else
                {
                    $result['error'] = "Une erreur est survenue, veuillez recommencer";
                    echo json_encode($result);
                }
            }
            else
            {
                $result['error'] = "Une erreur est survenue, le tags n'a pas été transmit";
                echo json_encode($result);
            }
        }
        else
        {
            $result['error'] = "Vous devez être connecté pour effectuer cette action";
            echo json_encode($result);
        }
    }

    /**
     * user sub to tag method
     * @param pass in class __construct
     */
    public function subToAction()
    {
        if($this->_authModule->isConnected())
        {
            if($this->_idTags != null && $this->_authModule->getLogin() != 0)
            {
                if($response = $this->_catModel->subToCat($this->_idTags, $this->_authModule->getLogin()))
                {
                    $result['success'] = "opération éffectuer avec succées";
                    echo json_encode($result);
                }
                else
                {
                     $result['error'] = "l'ajout en basse de donnée ne s'est pas déroulé correctement";
                     echo json_encode($result);
                }
            }
            else
            {
                $result['error'] = "Vous n'etes pas autorisez à effectuer cette action";
                echo json_encode($result);
            }
        }
        else
        {
            $result['error'] = "Vous devez être connecté pour effectuer cette action";
            echo json_encode($result);
        }
    }

    /**
     * unsub to tag method
     * @param pass in class _construct
     */
    public function unSubToAction()
    {
        if($this->_authModule->isConnected())
        {
            if($this->_idTags != null && $this->_authModule->getLogin() != 0)
            {
                if($response = $this->_catModel->unSubToCat($this->_idTags, $this->_authModule->getLogin()))
                {
                    $result['success'] = "opération éffectuer avec succées";
                    echo json_encode($result);
                }
                else
                {
                    $result['error'] = "la mise à jours des données ne s'est pas déroulé correctement";
                    echo json_encode($result);
                }
            }
            else
            {
                $result['error'] = "Vous n'etes pas autorisé à effectuer cette action";
                echo json_encode($result);
            }
        }
        else
        {
            $result['error'] = "Vous devez être connecté pour effectuer cette action";
            echo json_encode($result);
        }
    }

    /**
     * get all tag id subscribe by user
     * @param $user
     * @return bool
     */
    public function getCatSub($user)
    {
        if($user!= null & $user != "")
        {
            if($response['cat_sub'] = $this->_catModel->getSubInfo($user))
            {
                return $response;
            }
            else
            {
                return false;
            }
        }
    }
}

?>