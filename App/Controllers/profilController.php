<?php

class profilController extends coreController
{
    use imageFormat;

    private $_profilModels;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->_profilModels = new profilModels();
    }

    /**
     * Get and render user profil data (call by frontProfilController)
     * @param $request
     * @return View
     */
    public function showUserProfilAction($request)
    {

        $userID = ctype_digit($request->get("userID")) ? (int) $request->get("userID") : 0;

        if($this->_authModule->isConnected())
        {
            if($userID != 0 )
            {
                if($userProfil = $this->getProfil($userID))
                {
                    if( $userID === $this->_authModule->getLogin())
                    {
                        $this->_response = $this->renderP('profilAdminView', compact('userProfil'));
                    }
                    else
                    {
                        $this->_response = $this->renderP('profilView', compact('userProfil'));
                    }
                }
                else
                {
                    $error = "Une erreur est survenue lors de la recuperation du profil";

                    $this->_response = $this->renderP('profilAdminView', compact('error'));

                }
            }
            else
            {
                $error= "Vousn'avez pas les droit suffisant pour afficher cette page";

                $this->_response = $this->renderP('profilAdminView', compact('error'));
            }
        }
        else
        {
            $error = "Vous devez être connecter pour afficher cette page";

            $this->_response = $this->renderP('profilAdminView', compact('error'));

        }
       echo $this->getResponse();
    }

    /**
     * return user profil array data
     * @param $userID
     * @return mixed bool|array
     */
    private function getProfil($userID)
    {
        if($response['profil_info'] = $this->_profilModels->getProfilInfo($userID))
        {
           return $response;
        }
        else
        {
            return false;
        }
    }


    /**
     * get user profil data for public profil view
     * @param $userID
     * @return array|mixed|string
     */
    public function getPartialInfo($user)
    {
        $userID = intval($user) != 0 ? $user : 0;
        if($this->_authModule->isConnected())
        {
            if($userID != 0 && $this->profilExist($userID) )
            {
                if( $response = $this->_profilModels->getProfilSum($userID))
                {
                    if(isset($response[0]['u_profil_img']) && $response[0]['u_profil_img']!= null)
                    {
                        if($ext = explode(".", $response[0]['u_profil_img']))
                        {
                            $avatarSrc = "avatar/user_miniature_".$userID."/".$userID."_avatar_mini.".end($ext);
                            array_push($response, $avatarSrc);
                        }
                    }
                    return $response;
                }
                else
                {
                    $error = "Aucune information trouvez, le profil peux ne pas exister";

                    return $this->renderP("404", compact('error'));
                }
            }
            else
            {
                $error = "Vous ne pouvez pas récuperer ces information, l'identifiant est incorrecte";

                return $this->renderP("404", compact('error'));
            }
        }
        else
        {
            $error = "Vous devez etre connecter pour recuperer ces informations";

            return $this->renderP("404", compact('error'));
        }
    }


    /**
     * add a new user description function call by ajax process
     * @param $request
     * @return "json" notification
     */
    public function addProfilDescriptionAction($request)
    {
        $error = array();

        if($this->_user_id == intval($request->get('userID') && intval($request->get('userID')) == $this->_authModule->getLogin()))
        {
            if($request->get('modif') != "")
            {
                if(is_string($request->get('modif')))
                {
                    if(strlen($request->get('modif')) <= 255)
                    {
                        $dataDescription = htmlspecialchars($request->get('modif'));

                        if(($this->_profilModels->AddInfo($dataDescription, $this->_user_id )) == true)
                        {
                           $success = "Mis a jour avec succées";
                           echo json_encode($success);
                        }
                        else
                        {

                            $error['error'] =  "erreur : la modification n'a pas pu se faire";
                            echo json_encode($error);
                        }
                    }
                    else
                    {
                        $error['error'] = "Désolé, votre message est trop long. 255 caractère maximum";
                        echo json_encode($error);
                    }
                }
                else
                {
                    $error['error'] = "Désolé, votre message etre une chaine de caractère.";
                    echo json_encode($error);
                }
            }
            else
            {
                $error['error'] = "Désolé, vous ne pouvez pas soummettre un message vide.";
                echo json_encode($error);
            }
        }
    }

    /**
     * delete user profil description call by ajax process
     * @param $request
     * @return "json" notification
     */
    public function deleteDescriptionAction($request)
    {
        $userID = ctype_digit($request->get("userID")) ? (int) $request->get("userID") : 0;

        if($this->_authModule->isConnected())
        {
            if($userID != 0 && $userID === $this->_authModule->getLogin())
            {
                if( $response = $this->_profilModels->suppDescriptif($userID))
                {
                    $success = "Votre description à bien été supprimer";
                    echo json_encode($success);
                }
                else
                {
                    $error = array('error' => 'Un problèeme est survenue lors de la tentative de suppression');
                    echo json_encode($error);
                }
            }
            else
            {
                $error['error'] = "Vous n'avez pas les droits nécessaire pour effectuer cette action";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "Vous devez être connecté pour effectuer cette action";
            echo json_encode($error);
        }
    }

    /**
     * get user following and follower data
     * @param $user_id
     * @return array|bool|mixed|string
     */
    public function getAboList($user_id)
    {
        //var_dump($user_id);
        if(intval($user_id) != 0)
        {
                if(!empty($response = $this->_profilModels->getAboID($user_id)))
                {
                    if(is_array($response))
                    {
                        $listeAbo = $this->getAboLitName($response, $user_id);
                        $listeSub = $this->getListSub($user_id);

                        if($listeAbo && $listeSub != false)
                        {
                            $listeAbo['userFollowBack'] = $listeSub;

                            return $listeAbo;
                        }
                        elseif(is_array($listeSub) && $listeAbo == false )
                        {
                            return $listeSub;
                        }
                        else
                        {
                            return $listeAbo;
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    return false;
                }
        }
        else
        {
            $error = "erreur lors du traitement userID manquant - profilModels";
            return $this->renderP("404", compact('error'));
        }
    }

    /**
     * get user follower pseudo (private function)
     * @param array $data
     * @param $user
     * @return mixed
     */
    private function getAboLitName($data = [], $user)
    {
        $nameAbo = $this->_profilModels->getName($data, $user);
        return $nameAbo;
    }

    /**
     * get user following pseudo (private function)
     * @param $user
     * @return bool|mixed
     */
    private function getListSub($user)
    {

        $temp = $this->_profilModels->getSubList($user);

        if(!empty($temp))
        {
            return $temp;
        }
        else
        {
            return false;
        }
    }

    /**
     * check if userProfil exist
     * @param $user
     * @return bool
     */
    public function profilExist($user)
    {
        return $this->_profilModels->userExist($user);
    }

    /**
     * render list abo partial view
     * @param null $otherUser
     * @return array|mixed|string
     */
    public function renderAboList($otherUser = null)
    {
        if($this->_authModule->isConnected())
        {
            if( $userAbo =  $this->getAboList($this->_authModule->getLogin()))
            {
                if(is_array($userAbo))
                {
                    if($this->_user_id != 0)
                    {
                        $pageId = $this->_user_id;
                    }
                    elseif($otherUser != null)
                    {
                        $pageId = $this->_user_id = $otherUser;
                    }

                    $aboId = array();

                    foreach($userAbo['user_abonnement'] as $key => $value)
                    {
                        $aboId[] = $value['id_utilisateur'];
                    }
                }

                if($this->_user_id != $this->_authModule->getLogin())
                {
                    $listeAbo =  $this->getAboList($this->_user_id);

                }
                else
                {
                    $listeAbo = $userAbo;
                }

                return $this->renderP('aboView', compact('listeAbo', 'pageId', 'aboId'));
            }
        }
    }

    /**
     * subscribe method
     * @param $request
     */
    public function subAction($request)
    {
        $userID = ctype_digit($request->get("user_ID") ) ? (int) $request->get("user_ID") : 0 ;
        $userSubID = ctype_digit( $request->get('userSub_ID')) ? (int) $request->get('userSub_ID'): 0;
        $action = htmlspecialchars($request->get("actionType"));

        if($this->_authModule->isConnected() && $this->_authModule->getLogin() == $userID)
        {
            if($userSubID != 0)
            {
                switch ($action)
                {
                    case "sub":
                        $result = $this->_profilModels->userSubscribe($userID, $userSubID);
                        break;
                    case "unSub":
                        $result = $this->_profilModels->userUnsubscribe($userID, $userSubID);
                        break;
                }
                if($result)
                {
                    if($aboPartial["result"] = $this->renderAboList($userSubID))
                    {
                        echo json_encode($aboPartial);
                        return true;
                    }
                    else
                    {
                        echo json_encode('OK');
                        return true;
                    }
                }
                else
                {
                    $error['error'] = "une erreur s'est produite lors de la mise à jour en base de donnée";
                    echo json_encode($error);
                }
            }
            else
            {
                $error['error'] = "une erreur s'est produite, les données sont manquantes ou érronés";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "Vous devez etre connecté pour effectuer cette action";
            echo json_encode($error);
        }
    }

    /**
     * add avatar method + create thumn
     * @param $request
     */
public function sendImageAction($request)
{
    if($this->_authModule->isConnected())
    {
        $userId = ctype_digit($request->post("userID")) ? (int) $request->post("userID") : 0;
        $source = $request->post("from") != null ? htmlspecialchars($request->post("from")) : null;
        if($userId != 0 && $this->_authModule->getLogin() === $userId)
        {
            if($_FILES['file_img']['name'] != null && $source != null)
            {
                $path = $this->addImageAction($userId, $source);

                if(isset($path['error']))
                {
                    echo json_encode($path);
                    return false;
                }
                else
                {
                    $extension = explode(".",$path);

                    if( $result = $this->_profilModels->addAvatar( $this->_authModule->getLogin(), $path) )
                    {
                        $thumb_path = "avatar/user_miniature_".$this->_authModule->getLogin() ."/".$this->_authModule->getLogin()."_avatar_mini.".end($extension);

                        if( $this->make_thumb($path,$thumb_path,120) )
                        {
                            $response['miniature'] = $thumb_path;
                        }
                        else
                        {
                            $response['error'] = "pas marcher";
                        }


                        echo json_encode($response);
                    }
                    else
                    {
                        $error['error'] = "Une erreur est survenue, votre avatar n'a pas été enregistrer";

                        echo json_encode($error);
                        return false;
                    }
                }
            }
            else
            {
                $error['error'] = "Une erreur est survenue, votre avatar n'a pas été enregistrer";

                echo json_encode($error);
                return false;
            }

        }
    }
}

    /**
     * delete avatar method
     * @param $request
     * @return bool|json
     */
    public function suppAvatarAction($request)
    {
        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() == $this->_user_id)
            {
                if($response = $this->_profilModels->dropAvatar($this->_authModule->getLogin()))
                {
                    return true;
                }
                else
                {
                    $response['error'] = "une erreur s'est produite lors de la suppression";
                    echo json_encode($response);
                }
            }
            else
            {
                $response['error'] = "votre identifiant est incorrecte ";
                echo json_encode($response);
            }
        }

    }

    /**
     * update email info method
     * @param $request
     */
    public function changeMailAction($request)
    {
        $currentMail = filter_var($request->get("cMail"), FILTER_SANITIZE_EMAIL);
        $newMail = filter_var($request->get("nMail"), FILTER_SANITIZE_EMAIL);
        $userID = ctype_digit($request->get("userId")) ? (int) $request->get("userId") : 0;

        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() == $userID)
            {
                if(filter_var($currentMail, FILTER_VALIDATE_EMAIL))
                {
                    if(filter_var($newMail, FILTER_VALIDATE_EMAIL))
                    {
                        if($checkDispo = $this->_profilModels->checkMailDispo($newMail))
                        {
                            if($response = $this->_profilModels->addNewMail($newMail, $userID))
                            {
                                $result['success'] = "mail modifier avec succées";
                                echo json_encode($result);
                            }
                            else
                            {
                                $error['error'] = "Une erreur s'est produite lors de la mise à jours de votre profil, aucune modification n'a été validé";
                                echo json_encode($error);
                            }
                        }
                        else
                        {
                            $error['error'] = "Désolé, cette email existe deja dans notre base de données";
                            echo json_encode($error);
                        }
                    }
                    else
                    {
                        $error['error'] = "Vous devez fournir une addresse email valide";
                        echo json_encode($error);
                    }
                }
                else
                {
                    $error['error'] = "Une erreur s'est produite, veuillez rééssayer";
                    echo json_encode($error);
                }
            }
            else
            {
                $error['error'] = "Vous n'avez pas l'autorisation nécessaire pour effectuer cette action";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "Vous devez etre connecté pour effectuer cette action";
            echo json_encode($error);
        }
    }

    /**
     * update user pseudo method
     * @param $request
     */
    public function changePseudoAction($request)
    {
        $newPseudo = htmlspecialchars($request->get("data"));
        $userID = ctype_digit($request->get("user_id")) ? (int) $request->get("user_id") : 0;

        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() == $userID )
            {
                if(!empty($newPseudo))
                {
                    if( $this->_profilModels->checkPseudo($newPseudo) )
                    {
                        if( $this->_profilModels->addNewPseudo($newPseudo, $userID))
                        {
                            $result['success'] = "mise a jour de votre pseduo effectuer";
                            echo json_encode($result);
                        }
                        else
                        {
                            $error['error'] = "une erreur s'est produite, veuillez rééssayer ultérieurement";
                            echo json_encode($error);
                        }
                    }
                    else
                    {
                        $error['error'] = "Pseudo déjà pris";
                        echo  json_encode($error);
                    }
                }
                else
                {
                    $error['error'] = "Votre Pseudo ne peux pas être vide";
                    echo json_encode($error);
                }

            }
            else
            {
                $error['error'] = "vous n'etes pas autorisé à effectuer cette opération ";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "vous devez etre connecté pour effectuer cette opération ";
            echo json_encode($error);
        }
    }

    /**
     * update user last and first name method
     * @param $request
     */
    public function changeUserRealNamesAction($request)
    {
        $user = (int) $request->get("user_id") == $this->_authModule->getLogin() ? (int) $request->get("user_id") : 0;
        $last = $request->get("lastN") == "" ? "" : htmlspecialchars($request->get("lastN")) ;
        $first = $request->get("firstN") == "" ? "" : htmlspecialchars($request->get("firstN"));

        if($user != 0 && $this->_authModule->isConnected())
        {
            if(( $this->_profilModels->changeRealName($user,$first,$last)) == true)
            {
                $result['success'] = "vos informations on été mise a jour";
                echo json_encode($result);
            }
            else
            {
                $result['error'] = "une erreur s'est produite, veuillez nous en excusé";
                echo json_encode($result);
            }
        }
        else
        {
            $result['error'] = "vous devez etre connecté pour effectuer cette action";
            echo json_encode($result);
        }
    }

    /**
     * update user birthdate method
     * @param $request
     */
    public function changeBirthAction($request)
    {
        $user = ctype_digit($request->get("user_id")) ? (int) $request->get("user_id") : 0;
        $date = preg_replace("([^0-9-])", "", $request->get("date"));

        if($user == $this->_authModule->getLogin() && $this->_authModule->isConnected())
        {
            if(!empty(strtotime($date)))
            {
                $newDate = new DateTime($date);
                $currentDate = new DateTime();
                $interval = date_diff($currentDate,$newDate);

                if( ( (int) $interval->format("%y") ) >= 10)
                {
                    if( $response = $this->_profilModels->changeBirthDate($date, $user))
                    {
                        $result['success'] = "Date de naissance mis à jour";
                        echo json_encode($result);
                    }
                    else
                    {
                        $result['error'] = "Une erreur est survenue, veuillez nous en excusez";
                        echo json_encode($result);
                    }
                }
                else
                {
                    $result['error'] = "Désolé, vous etes trop jeune pour vous inscrire";
                    echo json_encode($result);
                }
            }
            else
            {
                $result['error'] = "Désolé, veuillez inserer une date de naissance valide";
                echo json_encode($result);
            }
        }
        else
        {
            $result['error'] = "vous devez etre connecté pour effectuer cette action";
            echo json_encode($result);
        }
    }

    /**
     * get all message send between two user in a tchat box
     * @param $request
     */
    public function showMessageAction($request)
    {
        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() == $this->_user_id)
            {
                $chatModel = new chatModels();

                if( $allMess = $chatModel->getAllPrivateMessage($this->_authModule->getLogin()))
                {
                    $pageId = $this->_authModule->getLogin();

                    $messView =  $this->renderP('messagePrivate', compact('allMess'));

                    $profilPartial = $this->getPartialInfo($this->_user_id);

                    $this->renderFinale('index', compact('messView', 'profilPartial','pageId'));

                    echo $this->getResponse();
                }
            }
        }
    }
}
?>