<?php

class pwittModels extends coreModel
{
    /**
     * gett all pwit + tag associate by user
     * @param $userID
     * @param null $repwitt
     * @return array|bool
     */
    public function getPwitt($userID, $repwitt = null )
    {
        $temp = (int) $userID;
        try {
            $req = $this->_pdo->prepare("SELECT p.id_pwit,
                        p.id_utilisateur, p.p_contenu,
                        p.p_auteur, p.p_img, p.p_date,u_profil_img,
                        ( SELECT GROUP_CONCAT(id_categorie) FROM appartient a                                        
                        WHERE a.id_pwit = p.id_pwit  ) as id_categories,
                        ( SELECT GROUP_CONCAT(cat_titre) FROM categorie c 
                        RIGHT JOIN appartient a2 
                        ON a2.id_categorie = c.id_categorie
                        WHERE a2.id_pwit = p.id_pwit ) as titre_categories
                        FROM pwit p  LEFT JOIN utilisateur ON p.id_utilisateur = utilisateur.id_utilisateur                                                                            
                        WHERE p.id_utilisateur = ?");
            if ($req->execute(array($temp)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
            {
                if (isset($repwitt))
                {
                    if ($response2 = $this->repwittMethod($repwitt))
                    {
                        if (isset($response))
                        {
                            $dataAll = array_merge($response, $response2);

                            return $dataAll;
                        }
                        return $response2;
                    }
                    else
                    {
                        return $response;
                    }
                }
                else
                {
                    return $response;
                }
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * save republication data in db
     * @param $repwittID
     * @return array|bool
     */
    public function repwittMethod($repwittID)
    {
        for($i = 0; $i < count($repwittID); $i++)
        {
            if( $req2 = $this->_pdo->prepare("SELECT *, utilisateur.u_profil_img FROM pwit LEFT JOIN utilisateur ON pwit.id_utilisateur = utilisateur.id_utilisateur WHERE id_pwit = ?"))
            {
                if(  $req2->execute(array($repwittID[$i]['id_pwit'])) && $response2 = $req2->fetchAll(PDO::FETCH_ASSOC) )
                {
                    $response2[0]['date_origin'] = $response2[0]['p_date'];
                    $response2[0]['p_date'] = $repwittID[$i]['r_date'];

                    if($response2[0]['p_img'] != null)
                    {
                        $ext = explode(".", $response2[0]['p_img']);
                        $thumb = 'pwit_image/user_miniature_'.$response2[0]['id_utilisateur'].'/'.$response2[0]['id_pwit'].'pwit.'.end($ext);
                        $response[0]['miniature'] = $thumb;
                    }
                    unset($response2[0]['u_mot_de_passe']);

                    $dataAll[] = $response2[0];
                }
            }
        }

       if(count($dataAll) >= 1)
       {
           return $dataAll;
       }

       return false;
    }

    /**
     * get all republication data from user
     * @param $user
     * @return array|bool
     */
    public function getRepwittID($user)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT * FROM repwitt WHERE id_utilisateur = ?");
            $req->execute(array($user));

            if($response = $req->fetchAll(PDO::FETCH_ASSOC))
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
     * save new publication from user in db and return last inserted id
     * @param null $content
     * @param $user
     * @param $pseudo
     * @param null $tag
     * @param null $path
     * @return bool|int
     */
    public function addNewPwitt($content = null, $user, $tag = null, $path = null)
    {
       try
        {
            if( $req = $this->_pdo->prepare("INSERT INTO pwit(p_contenu, id_utilisateur, p_auteur, p_date, p_img) 
                                            VALUES(?,?,(SELECT u_identifiant FROM utilisateur WHERE id_utilisateur = ?), NOW(), ?)"))
            {
                if($req->execute(array($content,$user,$user, $path)))
                {
                    $lastID = $this->_pdo->lastInsertId();

                    if($tag != null ) // add tag if exist
                    {
                        $catModel = new categorieModels();

                        if($catModel->addCat($tag, $lastID))
                        {
                            return $lastID;
                        }
                        else
                        {
                            return false;
                        }

                    }
                    return $lastID;
                }
                return false;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    /**
     * get last publication of user in db
     * @param $userID
     * @return array|bool
     */
    public function lastPwittUser($pwitID)
    {
        $req = $this->_pdo->prepare("SELECT p.id_pwit,
                        p.id_utilisateur, p.p_contenu,
                        p.p_auteur, p.p_img, p.p_date, COUNT(aime.id_pwit) as userLike, u.u_profil_img,
                        ( SELECT GROUP_CONCAT(id_categorie) FROM appartient a                                        
                        WHERE a.id_pwit = p.id_pwit  ) as id_categories,
                        ( SELECT GROUP_CONCAT(cat_titre) FROM categorie c 
                        LEFT JOIN appartient a2 
                        ON a2.id_categorie = c.id_categorie
                        WHERE a2.id_pwit = p.id_pwit ) as titre_categories
                        FROM pwit p LEFT JOIN aime ON aime.id_pwit = p.id_pwit 
                        LEFT JOIN utilisateur u ON p.id_utilisateur = u.id_utilisateur                                                                           
                        WHERE p.id_pwit = ? ORDER BY p.p_date DESC LIMIT 1");


         if( $req->execute(array($pwitID)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
         {
             return $response;
         }
         else
         {
             return false;
         }

    }


    /**
     * save republication data from user in db
     * @param $idPwitt
     * @param $userID
     * @return bool
     */
    public function doRepwitt($idPwitt, $userID)
    {
        try
        {
            $req2 = $this->_pdo->prepare("INSERT INTO repwitt (id_pwit, id_utilisateur, r_date) VALUES (?,?, NOW())");

            if($req2->execute(array($idPwitt, $userID)))
            {
                return true;
            }
            return false;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function allreadyExist($idPwit, $userID)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT * FROM repwitt WHERE id_pwit = ? AND id_utilisateur = ?");
            if($req->execute(array($idPwit, $userID)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
            {
                return true;
            }
            return false;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getLastRepwit($user)
    {
        $comModel = new commentaireModels();
        try
        {
            if($req = $this->_pdo->prepare("SELECT pwit.id_pwit, p_auteur, id_repwitt, r_date as p_date, pwit.p_date as date_origin, p_contenu, pwit.id_utilisateur, p_img, utilisateur.u_profil_img FROM pwit LEFT JOIN repwitt ON repwitt.id_pwit = pwit.id_pwit LEFT JOIN utilisateur ON pwit.id_utilisateur = utilisateur.id_utilisateur WHERE repwitt.id_utilisateur = ? ORDER BY repwitt.r_date DESC LIMIT 1"))
            {
                if($req->execute(array($user)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
                {
                    for($i =0; $i < count($response); $i++)
                    {
                        $response[$i]['countLike'] = $this->getCountLike($response[$i]['id_pwit']);
                        $response[$i]['userNameLike'] = $this->getUserLikeName($response[$i]['id_pwit']);
                        $response[$i]['userLike'] = $this->getUserLike($user, $response[$i]['id_pwit']);
                        $response[$i]['commentaire'] = $comModel->getComByPwit($response[$i]['id_pwit'], $user);
                    }
                    return $response;
                }
                return false;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * remove republication data from user in db
     * @param $user
     * @param $date
     * @param $idPwitt
     * @return bool
     */
    public function repwittRemove($user,$date,$idPwitt)
    {
        try
        {
            $req = $this->_pdo->prepare("DELETE FROM repwitt WHERE id_utilisateur = ? AND r_date = ? AND id_pwit = ?");
            if($req->execute(array($user,$date,$idPwitt)))
            {
                return true;
            }
            return false;

        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * delete a publication from user in db
     * @param $user
     * @param $idPwitt
     * @return bool
     */
    public function pwittDelete( $user, $idPwitt)
    {
        $catModel = new categorieModels();

        if($catModel->removeCatLink($idPwitt) && $this->removeLikePwit($idPwitt))
        {
            try
            {
                $req2 = $this->_pdo->prepare("DELETE FROM repwitt WHERE id_pwit = ?");

                if($req2->execute(array($idPwitt)))
                {
                    $req = $this->_pdo->prepare("DELETE FROM pwit WHERE id_utilisateur = ? AND id_pwit = ?");
                    if($req->execute(array($user,$idPwitt)))
                    {
                        return true;
                    }
                    return false;
                }
                else
                {
                    $req = $this->_pdo->prepare("DELETE FROM pwit WHERE id_utilisateur = ? AND id_pwit = ?");
                    if($req->execute(array($user,$idPwitt)))
                    {
                        return true;
                    }
                    return false;
                }
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }
    }

    public function isOwner($idPwitt, $userID)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT * FROM pwit WHERE id_pwit = ? AND id_utilisateur= ?");

            if($req->execute(array($idPwitt, $userID)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
            {
                return true;
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

    private function removeLikePwit($idPwitt)
    {
        try
        {
            $req = $this->_pdo->prepare("DELETE FROM aime WHERE id_pwit = ?");
            if($req->execute(array($idPwitt)))
            {
                return true;
            }
            return false;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }



   /* public function validateTag($tag)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT * FROM categorie WHERE titre_categorie = ?");
            $req->execute(array($tag));
            $response = $req->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($response))
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

    public function checkCatLink($idTag, $idPwitt)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT * FROM appartien WHERE id_categorie = ? AND id_pwit = ?");
            $req->execute(array($idTag, $idPwitt));
            $response = $req->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($response))
            {
                return true;
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
    }*/

    /*public function removeCatLink($id_pwit)
    {
        try
        {
          $req = $this->_pdo->prepare("DELETE FROM appartien WHERE id_pwit = ?");
          if($req->execute(array($id_pwit)))
          {
              return true;
          }
          return false;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }*/


    public function addLike($user,$pwitt)
    {
        try
        {
            if( $req = $this->_pdo->prepare("INSERT INTO aime(id_utilisateur, id_pwit) VALUES(?,?)"))
            {
                if($req->execute(array($user, $pwitt)))
                {
                    return true;
                }
                return false;
            }
        }catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getCountLike($id)
    {
        try
        {
            if( $req = $this->_pdo->prepare("SELECT COUNT(id_utilisateur) AS countLike FROM aime WHERE aime.id_pwit = ?"))
            {
                if( $req->execute(array($id)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
                {
                    return $response[0]['countLike'];
                }
                return 0;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getUserLikeName($id)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT 
                              (SELECT GROUP_CONCAT(u_identifiant) FROM utilisateur LEFT JOIN aime ON aime.id_utilisateur = utilisateur.id_utilisateur WHERE aime.id_pwit = ?) as userName,
                              (SELECT GROUP_CONCAT(a.id_utilisateur) FROM utilisateur a LEFT JOIN aime ON aime.id_utilisateur = a.id_utilisateur WHERE aime.id_pwit = ?) as UserID
                              FROM pwit WHERE id_pwit = ?");
            if( $req->execute(array($id, $id, $id)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
            {
                return $response;
            }
            return false;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getUserLike($userId, $idPwitt)
    {
        try
        {
            if( $req = $this->_pdo->prepare("SELECT * FROM aime WHERE id_utilisateur = ? AND id_pwit = ?"))
            {
                if($req->execute(array($userId, $idPwitt)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
                {
                    return true;
                }
                return false;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function dislike($user, $pwitt)
    {
        try
        {
            if( $req = $this->_pdo->prepare("DELETE FROM aime WHERE id_utilisateur = ? AND id_pwit = ?"))
            {
                if($req->execute(array($user, $pwitt)))
                {
                    return true;
                }
                return false;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
}