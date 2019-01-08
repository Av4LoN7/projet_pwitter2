<?php

class pwittController extends coreController
{
    use imageFormat;

    private $_pwittModel;
    private $_idPwitt;


    public function __construct($request)
    {
        parent::__construct($request);
        $this->_pwittModel = new pwittModels();
        $this->_idPwitt = ctype_digit($request->get('idPwitt')) ? (int) $request->get('idPwitt') : 0;
    }

    /**
     * add new pwit + image if send method
     * @param $request
     * @return bool|json
     */
    public function addPwittAction($request)
    {
        if($this->_authModule->isConnected())
        {
            $userValid = ctype_digit($request->post("userID")) ? (int) $request->post("userID") : 0;

            if($userValid != 0 && $this->_authModule->getLogin() == $userValid)
            {
                $source = $request->post("from") != null ? htmlspecialchars($request->post("from")) : null;
                $tags = $request->post('tag') != null ? htmlspecialchars($request->post('tag')) : null;
                $pwitContent = $request->post("pwittContent") != null ? htmlspecialchars($request->post("pwittContent")) : null;

                /**
                 * if file img is send succesfully
                 */
               if( $_FILES['file_img']['name'] != "" && $_FILES['file_img']['error'] == 0 && $source != null)
                {
                    $path = $this->addImageAction($userValid, $source);

                    if(isset($path['error']))
                    {
                        echo json_encode($path);
                        return false;
                    }
                    else
                    {
                        $extension = explode(".",$path);
                    }
                }// if file img is send but fail
                elseif($_FILES['file_img']['error'] > 0 && $_FILES['file_img']['name'] != "")
                {
                    $error['error'] = "Une erreur s'est produite, votre image n'est pas valide";
                    echo json_encode($error);
                    return false;
                }
                /**
                 * if not content and no img send
                 */
                if($pwitContent == null && $path == null)
                {
                    $error['error'] = "vous ne pouvez pas faire une publication vide";
                    echo json_encode($error);
                    return false;
                }
                else
                {
                    if(!isset($path)) // check if path was create
                    {
                        $path = null;
                    }

                    if( $dataSend = $this->_pwittModel->addNewpwitt($pwitContent, $userValid, $tags, $path) )
                    {
                        if($path != null)
                        {
                            $thumb_path = "pwit_image/user_miniature_".$userValid."/".$dataSend."pwit.".end($extension);
                            $this->make_thumb($path,$thumb_path,250);
                        }

                        if($lastPwitUser = $this->getLastPwitt($dataSend, $userValid) )
                        {
                            echo json_encode($lastPwitUser);
                        }
                        else
                        {
                            return false;
                        }
                    }
                }
            }
            else
            {
                $error['error'] = "vous devez être connecter pour pouvoir effectuer cette opération";
                echo json_encode($error);
                return false;
            }
        }
    }

    /**
     * get last pwit create by user
     * @param $userID
     * @return view|bool
     */
    private function getLastPwitt($pwitID, $userID)
    {
        if($pwitUser = $this->_pwittModel->lastPwittUser($pwitID))
        {
            $pageId = $userID;

            if($pwitUser[0]['p_img']!= null)
            {
                $pwitUser[0]['miniature_pwit'] = $this->makeThumbSrc($userID,"pwit",$pwitUser[0]['p_img'],$pwitUser[0]['id_pwit']);
            }
            if($pwitUser[0]['u_profil_img'] != null)
            {
                $pwitUser[0]['miniature'] = $this->makeThumbSrc($userID, "profil", $pwitUser[0]['u_profil_img']);
            }
            else
            {
                $pwitUser[0]['miniature'] = ABSPATH.'avatar/no_avatar/profile.jpg';
            }
            $this->_response = $this->renderP('pwitView', compact('pwitUser', 'pageId'));

            echo json_encode($this->getResponse());
        }
        else
        {
            $error['error'] = "Une erreur s'est produite, veuillez rafraichir votre page";
            echo json_encode($error);

            return false;
        }
    }

    /**
     * republication pwit method
     * @return bool
     */
    public function repwittAction($request)
    {
        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() && $this->_idPwitt != 0)
            {
                $pageId = ctype_digit($request->get("page")) ? (int) $request->get("page") : 0;

                if( $repwitExist = $this->_pwittModel->allreadyExist($this->_idPwitt, $this->_authModule->getLogin()))
                {
                    $error['error'] = "vous ne pouvez pas republier une seconde fois sans éffacer la republication";
                    echo json_encode($error);
                }
                else
                {
                    if($response = $this->_pwittModel->doRepwitt($this->_idPwitt, $this->_authModule->getLogin()))
                    {
                        if($pageId !=0 && $pageId == $this->_authModule->getLogin())
                        {
                            if( $pwitUser = $this->_pwittModel->getLastRepwit($this->_authModule->getLogin()))
                            {
                                $this->_response = $this->renderP('pwitView', compact('pwitUser', 'pageId'));

                                echo json_encode($this->getResponse());
                            }
                            else
                            {
                                $error['error'] = "Une erreur est suvenue";
                                echo json_encode($error);
                            }
                        }
                        else
                        {
                            $success['success'] = "C'est repwitter !";
                            echo json_encode($success);
                        }
                    }
                }
            }
            else
            {
                $error['error'] = "Vous n'avez pas le droit d'effectuer cette action";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "Vous devez être connecter pour éffectuer cette action";
            echo json_encode($error);
        }
    }

    /**
     * remove republication pwit method
     * @param $request
     * @return bool
     */
    public function removeRepwittAction($request)
    {
        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() && $this->_idPwitt != 0)
            {
                $date = $request->get("date") != null ? $request->get("date") : null;

                if($date != null)
                {
                    if($response = $this->_pwittModel->repwittRemove($this->_authModule->getLogin(), $date, $this->_idPwitt))
                    {
                        return true;
                    }
                    return false;
                }
            } else
            {
                $error['error'] = "Vous n'avez pas le droit d'effectuer cette action";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "Vous devez être connecter pour éffectuer cette action";
            echo json_encode($error);
        }
    }

    /**
     * delete publication method
     * @return bool
     */
    public function deletePwittAction()
    {
        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() && $this->_idPwitt !=0)
            {
                if($response = $this->_pwittModel->pwittDelete($this->_authModule->getLogin(), $this->_idPwitt))
                {
                    //var_dump($response);
                   return true;
                }
                return false;
            }
            else
            {
                $error['error'] = "Vous n'avez pas le droit d'effectuer cette action";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "Vous devez être connecter pour éffectuer cette action";
            echo json_encode($error);
        }
    }


    public function likePwittAction()
    {
        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() && $this->_idPwitt != 0)
            {
                if($response = $this->_pwittModel->addLike($this->_authModule->getLogin(), $this->_idPwitt))
                {
                    return true;
                }
                return false;
            }
            else
            {
                $error['error'] = "Vous n'avez pas le droit d'effectuer cette action";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "Vous devez être connecter pour éffectuer cette action";
            echo json_encode($error);
        }
    }


    public function dislikePwittAction()
    {
        if($this->_authModule->isConnected())
        {
            if ($this->_authModule->getLogin() && $this->_idPwitt != 0)
            {
                if ($response = $this->_pwittModel->dislike($this->_authModule->getLogin(), $this->_idPwitt))
                {
                    return true;
                }
                return false;
            }
            else
            {
                $error['error'] = "Vous n'avez pas le droit d'effectuer cette action";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "Vous devez être connecter pour éffectuer cette action";
            echo json_encode($error);
        }
    }

    // pas utile / voir frontprofil
    /*public function getLikeInfo($data)
    {
        $comControl = new commentaireController($this->_user);

        if($data != false)
        {
            for($i =0; $i < count($data); $i++)
            {

                $data[$i]['countLike'] = $this->_pwittModel->getCountLike($data[$i]['id_pwit']);
                $data[$i]['userNameLike'] = $this->_pwittModel->getUserLikeName($data[$i]['id_pwit']);
                $data[$i]['userLike'] = $this->_pwittModel->getUserLike($_SESSION['currentUser']['id_utilisateur'], $data[$i]['id_pwit']);
                $data[$i]['commentaire'] = $comControl->getAllCom($data[$i]['id_pwit']);
            }
            return $data;
        }
        else
        {
            return false;
        }

    }*/

}