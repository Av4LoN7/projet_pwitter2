<?php

class profilModels extends coreModel
{
   /* protected $_pdo;

    // return pdo instance
    public function __construct()
    {
        $this->_pdo = SPDO::getInstance()->getPDO();
    }*/

    /**
     * Get all data from an user
     * @param $user_id
     * @return bool|mixed
     */
    public function getProfilInfo($user_id)
    {
        try
        {
            if( $req = $this->_pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur =?"))
            {
                if( $req->execute(array($user_id)) && $response = $req->fetch(PDO::FETCH_ASSOC))
                {
                    if(!empty($response))
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
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * get all following user ID
     * @param $user_id
     * @return array|bool
     */
    public function getAboID($user_id)
    {
        try
        {
            if ($req = $this->_pdo->prepare('SELECT id_utilisateur_suivre FROM suivre WHERE id_utilisateur =?'))
            {
                if ($req->execute(array($user_id)) && $response = $req->fetchAll(PDO::FETCH_COLUMN))
                {

                    if (!empty($response))
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
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * Get all speudo fron following user ID
     * @param array $data
     * @param null $user
     * @return mixed
     */
    public function getName($data, $user = null)
    {
        if(is_array($data))
        {
            $data = implode(",", $data);
        }
        try
        {
            if( $req = "SELECT u_identifiant, id_utilisateur FROM utilisateur WHERE id_utilisateur IN ($data)")
            {
                if($stmt = $this->_pdo->prepare($req))
                {
                    if($stmt->execute() && $response['user_abonnement'] = $stmt->fetchAll(PDO::FETCH_ASSOC))
                    {
                        if($user != null && intval($user) != 0 )
                        {
                            return $this->getFollowBack($response, $user);
                        }
                    }
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * Get following back info from following user ID
     * @param $response
     * @param $user
     * @return mixed
     */
    private function getFollowBack($response, $user)
    {
        if(isset($response['user_abonnement']))
        {
            for($i =0; $i < count($response['user_abonnement']); $i++)
            {
                try
                {
                    if( $req = $this->_pdo->prepare("SELECT * FROM suivre WHERE id_utilisateur = ? AND id_utilisateur_suivre = ?"))
                    {
                        if( $req->execute(array($response['user_abonnement'][$i]['id_utilisateur'], $user)))
                        {
                            if($item = $req->fetchAll(PDO::FETCH_ASSOC))
                            {
                                //var_dump($item);
                                $response['user_abonnement'][$i]['followBack'] = true;
                            }
                        }
                    }
                }
                catch(Exception $e)
                {
                    echo $e->getMessage();
                }
            }
        }
        return $response;
    }

    /**
     * get follower user ID and Speudo info
     * @param $userID
     * @return bool|mixed
     */
    public function getSubList($userID)
    {
        if( $subList = $this->getSubID($userID))
        {
            if($dataSub = $this->getName($subList, $userID))
            {
                return $dataSub;
            }
            else
            {
                return false;
            }
        }
        return false;
    }

    /**
     * get follower user ID
     * @param $user_id
     * @return array|bool
     */
    private function getSubID($user_id)
    {
        try
        {
            if( $req = $this->_pdo->prepare('SELECT id_utilisateur FROM suivre WHERE id_utilisateur_suivre =?'))
            {
                if($req->execute(array($user_id)) && $response = $req->fetchAll(PDO::FETCH_COLUMN))
                {
                    if(!empty($response))
                    {
                        return $response;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            return false;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * get user profil summary info for front display
     * @param $user
     * @return array
     */
    public function getProfilSum($user)
    {
        try
        {
            if( $req = $this->_pdo->prepare("SELECT u_profil_img, u_identifiant, u_date_inscription, u_profil_description, id_utilisateur 
                                            FROM utilisateur WHERE id_utilisateur = ?"))
            {
                if( $req->execute(array($user) )&& $response = $req->fetchAll(PDO::FETCH_ASSOC) )
                {
                    if($response[0]['u_profil_img'] != null)
                    {
                        $ext = explode(".", $response[0]['u_profil_img']);
                        $thumb = 'avatar/user_miniature_'.$response[0]['id_utilisateur'].'/'.$response[0]['id_utilisateur'].'_avatar_mini.'.end($ext);
                        $response['miniature'] = $thumb;
                    }
                    return $response;
                }
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * update or add an user profil description
     * @param $data
     * @param $userID
     * @return bool
     */
    public function addInfo($data, $userID)
    {
        if(!empty($data) && $data != null)
        {
            try
            {
                if($req = $this->_pdo->prepare('UPDATE utilisateur SET u_profil_description = ? WHERE id_utilisateur = ?'))
                {
                    if($req->execute(array($data,$userID)))
                    {
                        return true;
                    }
                }
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }
        return false;
    }

    /**
     * Delete user profil description
     * @param $user
     * @return bool
     */
    public function suppDescriptif($user)
    {
        try
        {
            if( $req = $this->_pdo->prepare("UPDATE utilisateur SET u_profil_description = null WHERE id_utilisateur = ?"))
            {
                if($req->execute(array($user)))
                {
                    return true;
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * check if an user ID profil exist before display action
     * @param $user
     * @return bool
     */
    public function userExist($user)
    {
        try
        {
            if( $req = $this->_pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur= ?"))
            {
                if($req->execute(array($user)) && count($req->fetchAll(PDO::FETCH_ASSOC)) >0)
                {
                    return true;
                }
            }

        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * user Subscribe method
     * @param $user
     * @param $otherUser
     * @return bool
     */
    public function userSubscribe($user, $otherUser)
    {
        try
        {
            if( $req = $this->_pdo->prepare("INSERT INTO suivre (id_utilisateur, id_utilisateur_suivre) VALUES (?,?)") )
            {
                if($req->execute(array($user,$otherUser)))
                {
                    return true;
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * user unSubscribe method
     * @param $user
     * @param $otherUser
     * @return bool
     */
    public function userUnsubscribe($user, $otherUser)
    {
        try
        {
            if( $req = $this->_pdo->prepare("DELETE FROM suivre WHERE id_utilisateur = ? AND id_utilisateur_suivre = ? "))
            {
                if($req->execute(array($user,$otherUser)))
                {
                    return true;
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    //////////**********
    public function getImage($id)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT u_profil_img FROM utilisateur WHERE id_utilisateur = ?");
            $req->execute(array($id));

            if($response = $req->fetch(PDO::FETCH_ASSOC))
            {
                return $response;
            }
            else
            {
                return false;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * check if the email already exist in db
     * @param $newMail
     * @return bool
     */
    public function checkMailDispo($newMail)
    {
        try
        {
            if( $req = $this->_pdo->prepare("SELECT * FROM utilisateur WHERE u_email = ?"))
            {
                if($req->execute(array($newMail)) && $response = $req->fetchAll(PDO::FETCH_ASSOC) )
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * update the user email field in db
     * @param $newMail
     * @param $user
     * @return bool
     */
    public function addNewMail($newMail, $user)
    {
        try
        {
            if( $req = $this->_pdo->prepare("UPDATE utilisateur SET u_email = ? WHERE id_utilisateur = ?"))
            {
                if($req->execute(array($newMail, $user)))
                {
                    return true;
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * check if the pseduo already exist in db
     * @param $pseudo
     * @return bool
     */
    public function checkPseudo($pseudo)
    {
        try
        {
            if( $req = $this->_pdo->prepare("SELECT u_identifiant FROM utilisateur WHERE u_identifiant = ?"))
            {
                if( $req->execute(array($pseudo)) && $response = $req->fetch(PDO::FETCH_ASSOC) )
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * update user pseudo field in db
     * @param $pseudo
     * @param $user
     * @return bool
     */
    public function addNewPseudo($pseudo, $user)
    {
        try
        {
            if( $req = $this->_pdo->prepare("UPDATE utilisateur SET u_identifiant = ? WHERE id_utilisateur = ?"))
            {
                if($req->execute(array($pseudo, $user)))
                {
                    return true;
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * update user first and last name in db
     * @param $user
     * @param string $first
     * @param string $last
     * @return bool
     */
    public function changeRealName($user, $first = "", $last = "")
    {
        try
        {
            if($first && $last != "")
            {
                if ($req = $this->_pdo->prepare("UPDATE utilisateur SET u_nom = ?, u_prenom= ? WHERE id_utilisateur = ?"))
                {
                    if ($req->execute(array($first, $last, $user)))
                    {
                        return true;
                    }
                }
            }
            elseif($first == "")
            {
                if( $req = $this->_pdo->prepare("UPDATE utilisateur SET u_prenom = ? WHERE id_utilisateur = ?"))
                {
                    if($req->execute(array($last,$user)))
                    {
                        return true;
                    }
                }
            }
            else
            {
                if( $req = $this->_pdo->prepare("UPDATE utilisateur SET u_nom = ? WHERE id_utilisateur = ?"))
                {
                    if($req->execute(array($first,$user)))
                    {
                        return true;
                    }
                }

            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * udpate user birthdate in db
     * @param $date
     * @param $user
     * @return bool
     */
    public function changeBirthDate($date, $user)
    {
        try
        {
            if( $req = $this->_pdo->prepare("UPDATE utilisateur SET u_date_de_naissance = ? WHERE id_utilisateur = ?"))
            {
                if($req->execute(array($date,$user)))
                {
                    return true;
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * add avatar in db
     * @param $user
     * @param $path
     * @return bool
     */
    public function addAvatar($user, $path)
    {
        try
        {
            $req = $this->_pdo->prepare("UPDATE utilisateur SET u_profil_img = ? WHERE id_utilisateur = ?");
            if($req->execute(array($path, $user)))
            {
                return true;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * delete avatar from db
     * @param $userID
     * @return bool
     */
    public function dropAvatar($userID)
    {
        try
        {
            $req = $this->_pdo->prepare("UPDATE utilisateur SET u_profil_img = null WHERE id_utilisateur = ?");
            if($req->execute(array($userID)))
            {
                return true;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * get all follwing user
     * @param $userID
     * @return array
     */
    public function getFollowing($userID)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT utilisateur.id_utilisateur, u_profil_img, u_identifiant 
                                        FROM utilisateur 
                                        LEFT JOIN suivre ON utilisateur.id_utilisateur = suivre.id_utilisateur_suivre 
                                        WHERE suivre.id_utilisateur_suivre
                                        IN (SELECT suivre.id_utilisateur_suivre FROM suivre WHERE suivre.id_utilisateur = ?) 
                                        AND suivre.id_utilisateur = ?");

            if($req->execute(array($userID,$userID)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
            {
                foreach($response as $key => $value)
                {
                    $response[$key]['following'] = true;
                }

                return $response;
            }
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * get all follower
     * @param $userID
     * @return array
     */
    public function getFollower($userID)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT utilisateur.id_utilisateur, u_profil_img, u_identifiant 
                                        FROM utilisateur LEFT JOIN suivre on utilisateur.id_utilisateur = suivre.id_utilisateur 
                                        WHERE suivre.id_utilisateur_suivre = ?");
            if($req->execute(array($userID)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
            {
                foreach($response as $key => $value)
                {
                    $response[$key]['follower'] = true;
                }

                return $response;
            }
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * get all follower and following privatemessageView
     * @param $user
     * @return mixed
     */
    public function getAllFollow($user)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT utilisateur.id_utilisateur, utilisateur.u_identifiant, utilisateur.u_profil_img 
                                          FROM utilisateur 
                                          WHERE id_utilisateur IN (SELECT suivre.id_utilisateur_suivre FROM suivre WHERE suivre.id_utilisateur = ?) 
                                          OR utilisateur.id_utilisateur IN ( SELECT suivre.id_utilisateur FROM suivre WHERE suivre.id_utilisateur_suivre =? )");
            if($req->execute(array($user,$user)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
            {
                return $response;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
}