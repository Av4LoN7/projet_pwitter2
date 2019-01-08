<?php

class adminController extends coreController
{
    private $_user;
    private $_adminModel;


    public function __construct($request)
    {
        parent::__construct($request);
        $this->_adminModel = new adminModels();
        $this->_user = ctype_digit($request->post("user")) ? (int) $request->post("user") : 0;
    }

    /**
     * delete profil from user or admin
     * @param $request
     */
    public function deleteProfilAction($request)
    {
        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() == $this->_user && $this->_user == $_SESSION['currentUser']['auth'] )
            {
                if($response = $this->_adminModel->deleteUserProfil($this->_user))
                {
                    $result['success'] = "votre profil viens d'etre supprimé";
                    echo json_encode($result);
                }
            }
            elseif($this->_authModule->getRang() == 2)
            {
                if($response = $this->_adminModel->deleteUserProfil($this->_user))
                {
                    $result['admin'] = "le profil viens d'etre supprimmé";
                    echo json_encode($result);

                }
            }
            else
            {
                $result['error'] = "Vous n'etes pas autorisez à effectuer cette action";
                echo json_encode($result);
                return false;
            }
        }
    }

    /**
     * changeRank user method, availiable for admin user
     * @param $request
     * @return bool
     */
    public function changeRankAction($request)
    {
        $newRank = ctype_digit($request->post("rank")) ? (int) $request->post("rank") : 0;

        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() == $_SESSION['currentUser']['auth'] && $this->_authModule->getRang() == 2)
            {
                if($newRank != 0 && $this->_user != 0)
                {
                    if($response = $this->_adminModel->changeRank($this->_user,$newRank))
                    {
                        $result['success'] = "changement de rang effectuer";
                        echo json_encode($result);
                    }
                    else
                    {
                        $result['error'] = "une erreur s'est produite, veuillez recommencer";
                        echo json_encode($result);
                        return false;
                    }
                }
            }
        }
    }

    public function suppUserListAction()
    {
        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() == $_SESSION['currentUser']['auth'] && $this->_authModule->getRang() == 2)
            {
                if( $allUser = $this->_adminModel->getAllUserForSupp($this->_authModule->getLogin()))
                {
                    $suppView = $this->renderP('suppListAdminView', compact('allUser'));

                    $this->renderFinale('admin', compact('suppView'));

                    echo $this->getResponse();
                }

            }
        }
    }
}