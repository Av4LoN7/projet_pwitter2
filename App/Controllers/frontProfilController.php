<?php

class frontProfilController extends coreController {

use imageFormat;

    private $_pwitModels;
    private $_catModels;
    private $_profilModels;
    private $_comModels;
    private $_searchModel;
    private $_searchModule;

    public function __construct($request)
    {
        parent::__construct($request);

        $this->_pwitModels = new pwittModels();
        $this->_catModels = new categorieModels();
        $this->_profilModels = new profilModels();
        $this->_comModels = new commentaireModels();
        $this->_searchModel = new searchModels();
        $this->_searchModule = new search();
    }

    /**
     * render user public front page
     * @return view
     */
    public function frontPageAction($request)
    {
        if($this->_authModule->isConnected() && $this->_authModule->getLogin() != 0)
        {
            // check if user exist
            if($this->_profilModels->userExist($this->_user_id))
            {
                // check if option parameter is send
                if($option = $request->get("option"))
                {
                    // unset session message if exist while changing page

                    $this->unsetSessionMessage($option);
                    switch ($option)
                    {
                        // show follwing list
                        case "showAbonner":
                            $renderFollowing = $this->showAbo("abonner");

                            break;
                            //showFollwer list
                        case "showAbonnement":
                            $renderFollowing= $this->showAbo("abonnement");

                            break;
                            // search on the site
                        case "search":
                            $term = $request->get("term") != null ? htmlspecialchars($request->get("term")) : null;
                            $from = $request->get("from") != null ? htmlspecialchars($request->get("from")) : null;
                            $searchData = $this->_searchModule->searchTermAction($term,$from, $this->_searchModel);

                            break;
                            //show publication by cat
                        case "pwitByCat":
                            $idCat = ctype_digit($request->get("idCat")) ? (int) $request->get("idCat") : 0;
                            $pwitByCatRender = $this->showPwitByCat($idCat);

                            break;
                            // show messagerie board
                        case "showMessagerie":
                                $allFollow = $this->messagerieProcess($this->_user_id);
                            break;
                    }
                }
                if(!$option)
                {
                    // unset message session
                    $this->unsetSessionMessage();

                    $pwitRender = $this->renderPwit();
                }

                $profilPartial = $this->renderProfilSum(); // render profil sum

                $catPartial = $this->renderCatView(); // render list cat

                $aboPartial = $this->getAboData(); // render abo data id

                $pageId = $this->_user_id; // assign the id of the page

                if(isset($searchData)) // for result search display
                {
                    $searchResult = $this->makeThumbSearch($searchData);

                    $search = $this->renderP('searchView', compact('searchResult', 'term', 'from'));
                }

                $this->renderFinale('index', compact('pwitRender','catPartial', 'aboPartial', 'profilPartial', 'pageId', 'renderFollowing', 'search', 'pwitByCatRender', 'allFollow', 'boxMessageView'));
            }
            else
            {
                $error = "Ce profil n'existe pas ou vous n'avez pas les droits suffisant pour l'afficher";

                $this->_response = $this->renderP('404', compact('error'));
            }
        }
        else
        {

            $error = "Vous devez etre connecté pour afficher cette page";

            $this->_response = $this->renderP('403', compact('error'));

        }

        echo $this->getResponse();
    }

    // make thumb for user reseacrh
    private function makeThumbSearch($searchData)
    {
        if (isset($searchData['search']) && count($searchData['search']) >= 1)
        {
            for ($i = 0; $i < count($searchData['search']); $i++)
            {
                if ($searchData['search'][$i]['u_profil_img'] != null)
                {
                    $searchData['search'][$i]['miniature'] = $this->makeThumbSrc($searchData['search'][$i]['id_utilisateur'], "profil", $searchData['search'][$i]['u_profil_img']);
                }
                else
                {
                    $searchData['search'][$i]['miniature'] = "../../projet_pwitter/avatar/no_avatar/profile.jpg";
                }
            }
        }
        return $searchData;
    }

    /**
     * get and format user pwit and repwit with info
     * @return array|mixed|string|view
     */
    private function renderPwit()
    {
        $repwittTest = $this->_pwitModels->getRepwittID($this->_user_id);
        $pageId = $this->_user_id;

        if($repwittTest)
        {

            $pwitUser = $this->_pwitModels->getPwitt( $this->_user_id, $repwittTest);
        }
        else
        {
            $pwitUser = $this->_pwitModels->getPwitt($this->_user_id);
        }

        if(!is_null($pwitUser))
        {
            // classer les pwit par date
            usort($pwitUser, array($this, "sortByDate"));
        }


        if(is_array($pwitUser) && count($pwitUser) >=1)
        {
            for($i =0; $i < count($pwitUser); $i++)
            {
                if($pwitUser[$i]['p_img'] != null)
                {
                    $pwitUser[$i]['miniature_pwit'] = $this->makeThumbSrc($pwitUser[$i]['id_utilisateur'], "pwit", $pwitUser[$i]['p_img'], $pwitUser[$i]['id_pwit']);
                }

                if($pwitUser[$i]['u_profil_img'] != null)
                {
                    $pwitUser[$i]['miniature'] = $this->makeThumbSrc($pwitUser[$i]['id_utilisateur'], "profil", $pwitUser[$i]['u_profil_img'], $pwitUser[$i]);
                }
                else
                {
                    $pwitUser[$i]['miniature'] = "../../projet_pwitter/avatar/no_avatar/profile.jpg";
                }
                $pwitUser[$i]['countLike'] = $this->_pwitModels->getCountLike($pwitUser[$i]['id_pwit']);
                $pwitUser[$i]['userNameLike'] = $this->_pwitModels->getUserLikeName($pwitUser[$i]['id_pwit']);
                $pwitUser[$i]['userLike'] = $this->_pwitModels->getUserLike($this->_authModule->getLogin(), $pwitUser[$i]['id_pwit']);
                $pwitUser[$i]['pwitOwner'] = $this->_pwitModels->isOwner($pwitUser[$i]['id_pwit'], $this->_authModule->getLogin());
                $pwitUser[$i]['commentaire'] = $this->_comModels->getComByPwit($pwitUser[$i]['id_pwit'], $this->_authModule->getLogin());

            }
        }
        else
        {
            $pwitUser = null;
        }

       return $this->renderP('pwitView', compact('pwitUser', 'pageId'));

    }

    /**
     * get user categorie subcription
     * @return array|bool|mixed|string
     */
    private function renderCatView()
    {

        if($catSub = $this->_catModels->getSubInfo($this->_user_id))
        {
            return  $this->renderP('catView', compact('catSub'));
        }
        return false;
    }

    /**
     * render user sub view
     * @return array|mixed|string
     */
    private function getAboData()
    {
        $pageId = $this->_user_id;
        $aboId = array();

        if( $listeAbo = $this->renderAboData($this->_authModule->getLogin()))
        {
            foreach($listeAbo['user_abonnement'] as $key => $value)
            {
                $aboId[] = $value['id_utilisateur'];
            }
        }

        return $this->renderP('aboView', compact('listeAbo', 'pageId', 'aboId'));
    }

    /**
     * get user sub data
     * @param $userID
     * @return bool|mixed
     */
    private function renderAboData($userID)
    {
       if( $aboID = $this->_profilModels->getAboID($userID))
       {
           $listeAbo = $this->_profilModels->getName($aboID, $userID);
       }

        $listeSub = $this->_profilModels->getSubList($userID);


           if(isset($listeAbo) && isset($listeSub))
           {
               $listeAbo['userFollowBack'] = $listeSub;

               return $listeAbo;
           }
           elseif(is_array($listeSub) && !isset($listeAbo))
           {
               return $listeSub;
           }
           elseif(isset($listeAbo))
           {
               return $listeAbo;
           }
           else
           {
               return false;
           }
    }

    /**
     * get user summary data profil
     * @return array
     */
    private function renderProfilSum()
    {
        if( $info = $this->_profilModels->getProfilSum($this->_user_id))
        {
            if(isset($info[0]['u_profil_img']) && $info[0]['u_profil_img']!= null)
            {
                $info[0]['miniature'] = $this->makeThumbSrc($this->_user_id,"profil",$info[0]['u_profil_img']);
            }
        }

        $result = $this->renderP('partialProfilView', compact('info'));
        return $result;
    }

    private function sortByDate( $a, $b )
    {
        return (strtotime($a["p_date"]) < strtotime($b["p_date"])) ? 1 : -1;
    }


    /**
     * show folloer or following content
     * @param $from
     * @return bool|mixed|string
     */
    private function showAbo($from)
    {
        if($from)
        {
            switch($from)
            {
                case "abonner":
                    $followingList = $this->_profilModels->getFollowing($this->_user_id);
                    break;
                case "abonnement":
                    $followingList = $this->_profilModels->getFollower($this->_user_id);
                    break;
                default:
                    return false;

            }
            if(isset($followingList)&& count($followingList) >=1)
            {
                for($i =0; $i < count($followingList); $i++)
                {
                    if($followingList[$i]['u_profil_img'] != null)
                    {
                        $followingList[$i]['miniature'] = $this->makeThumbSrc($followingList[$i]['id_utilisateur'], "profil", $followingList[$i]['u_profil_img']);
                    }
                    else
                    {
                        $followingList[$i]['miniature'] = '../../projet_pwitter/avatar/no_avatar/profile.jpg';
                    }
                }
            }

            return $this->renderP('followerView', compact('followingList'));
        }
    }

    /**
     * render pwit by cat
     * @return view
     */
    private function showPwitByCat($idCat)
    {
            if($idCat !=0)
            {
                $pwitUser = $this->_catModels->getPwitByCat($idCat);
            }
            // faire le make thumb pour les pwit!!

        return $this->renderP('pwittByCategorie', compact('pwitUser'));
    }

    /**
     * messagerie display process
     * @param $user
     * @return bool|mixed|string
     */
    private function messagerieProcess($user)
    {
        if($user != $this->_authModule->getLogin())
        {
            $error = "vous ne pouvez pas effectuer cette action";

            $this->_response = $this->renderP('404', compact('error'));
            echo $this->getResponse();
            die();
        }
        else
        {
            if( $allSub = $this->_profilModels->getAllFollow($this->_authModule->getLogin()))
            {
                for($i = 0; $i < count($allSub); $i++)
                {
                    if($allSub[$i]['u_profil_img']!= null)
                    {
                        $allSub[$i]['miniature'] = $this->makeThumbSrc($allSub[$i]['id_utilisateur'],"profil",$allSub[$i]['u_profil_img']);
                    }
                }
            }
            return $this->renderP('boxMessageView', compact('allSub'));
        }
    }

    /**
     * unset session['message'] process / for messagerie view action
     * @param null $option
     */
    private function unsetSessionMessage($option = null)
    {
        if($option != "showMessagerie" || $option == null)
        {
            if(isset($_SESSION['messageBox']))
            {
                unset($_SESSION['messageBox']);
                unset($_SESSION['abo']);
            }
        }
    }

    /**
     * get all  follower and follwing then return view
     * @param $request
     */
    public function showMessagerieBoxAction($request)
    {
        $abo = ctype_digit($request->get("aboID")) ? (int) $request->get("aboID") : 0;
        $this->unsetSessionMessage();

        if($abo !=0 && $this->_user_id == $this->_authModule->getLogin())
        {
            $chat = new chatModels();
            if( $dialog = $chat->getMessage($this->_authModule->getLogin(), $abo))
            {
                $_SESSION['messageBox'] = $dialog;
                $_SESSION['abo'] = $abo;

                echo json_encode( $this->renderP('messagerieView', compact('dialog', 'abo')));
            }
            else
            {
                $error = "aucun messages trouvé";
                echo json_encode($this->renderP('messagerieView', compact('abo', 'error')));
            }

        }
    }

    public function adminAction()
    {
       // var_dump($this->_authModule->getRang() == 2 );
        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() == $_SESSION['currentUser']['auth'])
            {
                if($this->_authModule->getRang() == 2 && $_SESSION['currentUser']['rang'] == 2)
                {
                    $user = $this->_authModule->getLogin();

                    $admin = $this->renderP("adminView",compact('user'));

                    $this->renderFinale('admin', compact('admin'));
                    echo $this->getResponse();

                }
                else
                {
                    $error = "vous n'avez pas le droit d'afficher cette page";
                    $this->_response = $this->renderP("404",compact('error'));
                }
            }
            else
            {
                $error = "Une anomaly est detecter sur votre compte";
                $this->_response = $this->renderP("404",compact('error'));
            }
        }
        else
        {
            $error = "vous devez etre connecter pour effectuer cette action";
            $this->_response = $this->renderP("404",compact('error'));
        }
    }

}

?>