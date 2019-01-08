<?php

class commentaireModels extends coreModel
{

    /**
     * add new com on pwit in db return lastinsrtid
     * @param $idPwit
     * @param $user
     * @param $com
     * @param null $img
     * @return bool|int
     */
    public function addComm($idPwit, $user, $com, $img = null)
    {
        try
        {
            $req = $this->_pdo->prepare("INSERT INTO `commentaire` (c_contenu, c_img, c_date, commentaire.id_utilisateur) VALUES ( ?, ?, CURRENT_TIMESTAMP, ?);");
            if($req->execute(array($com, $img, $user)))
            {
                $lastID = $this->_pdo->lastInsertId();

                $req2 = $this->_pdo->prepare("INSERT INTO possede(id_pwit, id_commentaire) VALUES(?,?)");

                if($req2->execute(array($idPwit,$lastID)))
                {
                    return (int) $lastID;
                }
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
     * get last insert com from user
     * @param $idCom
     * @return array|bool
     */
    public function getLastComm($idCom)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT commentaire.id_commentaire, c_contenu, c_date,c_img, commentaire.id_utilisateur,u_identifiant,id_pwit FROM commentaire LEFT JOIN utilisateur ON commentaire.id_utilisateur = utilisateur.id_utilisateur LEFT JOIN possede ON commentaire.id_commentaire = possede.id_commentaire WHERE commentaire.id_commentaire = ?");
            $req->execute(array($idCom));
            if($response['commentaire'] = $req->fetchAll(PDO::FETCH_ASSOC))
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
     * get all com from select pwit
     * @param $idPwit
     * @return array|bool
     */
    public function getComByPwit($idPwit, $userID)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT commentaire.id_commentaire, c_contenu, c_date,c_img, commentaire.id_utilisateur,u_identifiant,id_pwit FROM commentaire LEFT JOIN possede ON commentaire.id_commentaire = possede.id_commentaire LEFT JOIN utilisateur ON commentaire.id_utilisateur = utilisateur.id_utilisateur WHERE possede.id_pwit = ?");
            $req->execute(array($idPwit));
            if($response = $req->fetchAll(PDO::FETCH_ASSOC))
            {
                for($i = 0; $i < count($response); $i++)
                {
                    if($this->isOwner( (int) $response[$i]['id_commentaire'], $userID))
                    {
                        $response[$i]['isOwner'] = true;
                    }
                    else
                    {
                        $response[$i]['isOwner'] = false;
                    }
                }
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
     * verify if user is owner of the com (for delete com purpose)
     * @param $idCom
     * @param $userID
     * @return bool
     */
    public function isOwner($idCom, $userID)
    {
        try
        {
            if( $req = $this->_pdo->prepare("SELECT * FROM commentaire WHERE id_commentaire = ? AND id_utilisateur = ?"))
            {
                if($req->execute(array($idCom, $userID)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
                {
                    return true;
                }
                else
                {
                    return false;
                }

            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * delete select com on pwit in db
     * @param $idCom
     * @return bool
     */
    public function deleteCom($idCom)
    {
        try
        {
            if($req = $this->_pdo->prepare("DELETE FROM commentaire WHERE id_commentaire = ?"))
            {
                if($req->execute(array($idCom)))
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

?>