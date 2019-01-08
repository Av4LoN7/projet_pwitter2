<?php

class categorieModels extends coreModel
{

    /**
     * remove relation between pwit and tags
     * @param $idCat
     * @param $idPwitt
     * @return bool
     */
    public function removeCat($idCat, $idPwitt)
    {
        try {
            if ($req = $this->_pdo->prepare("DELETE FROM appartient WHERE id_categorie=? AND id_pwit = ?")) {
                if ($req->execute(array($idCat, $idPwitt))) {
                    return true;
                }
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * add tag on pwit in db method
     * @param $tags
     * @param $pwitID
     * @return bool
     */
    public function addCat($tags, $pwitID)
    {
            $tempTag = explode(",", $tags);

            for ($i = 0; $i < count($tempTag); $i++)
            {
                // check if tags allready exist in db, if so get his id
                if ($test = $this->validateTag($tempTag[$i]))
                 {
                     // check if tag is allready link to pwit
                     if( $this->checkCatLink($test, $pwitID))
                     {
                         $this->linkTagToPwit($test, $pwitID);
                     }
                 }
                 else
                 {
                     try {

                         // add new tag in db
                         if ($req = $this->_pdo->prepare("INSERT INTO categorie(cat_titre) VALUES(?)"))
                         {
                             if ($req->execute(array($tempTag[$i])))
                             {
                                 // get the last insert tag id from db
                                 $lastIdCat = $this->_pdo->lastInsertId();

                                 $this->linkTagToPwit($lastIdCat, $pwitID);
                             }
                         }
                     }
                     catch (Exception $e)
                     {
                         echo $e->getMessage();
                     }
                 }
            }
            return true;
    }

    /**
     * check if tag allready exist if so return id tag
     * @param $tag
     * @return bool|int
     */
    private function validateTag($tag)
    {
        try {
            if ($req = $this->_pdo->prepare("SELECT id_categorie FROM categorie WHERE cat_titre = ?")) {
                if ($req->execute(array($tag)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
                {
                    return $response[0]['id_categorie'];
                }
                else
                {
                    return false;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * link tag on pwit method
     * @param $idCat
     * @param $idPwit
     * @return bool
     */
    private function linkTagToPwit($idCat, $idPwit)
    {
        try {
            if ($req = $this->_pdo->prepare("INSERT INTO appartient(id_pwit, id_categorie) VALUES(?,?)")) {
                if ($req->execute(array($idPwit, $idCat))) {
                    return true;
                }
                else
                {
                    return false;
                }

            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * check if tag allready link on target pwit
     * @param $idTag
     * @param $idPwitt
     * @return bool
     */
    private function checkCatLink($idTag, $idPwitt)
    {
        try {
            if ($req = $this->_pdo->prepare("SELECT * FROM appartient WHERE id_categorie = ? AND id_pwit = ?")) {
                if ($req->execute(array($idTag, $idPwitt)) && $response = $req->fetchAll(PDO::FETCH_ASSOC)) {
                    return false;
                }
                else
                {
                    return true;
                }

            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * use when deleting pwit to remove link to tag
     * @param $id_pwit
     * @return bool
     */
    public function removeCatLink($id_pwit)
    {
        try
        {
            $req = $this->_pdo->prepare("DELETE FROM appartient WHERE id_pwit = ?");
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
    }

    /**
     * get all pwit associate with a specific tag
     * @param $idCat
     * @return array|bool
     */
    public function getPwitByCat($idCat)
    {
        //var_dump($idCat);
        try
        {
            $req = $this->_pdo->prepare("SELECT * FROM pwit LEFT JOIN appartient ON appartient.id_pwit = pwit.id_pwit WHERE appartient.id_categorie = ? ORDER BY pwit.p_date DESC ");
            $req->execute(array($idCat));

            if($response = $req->fetchAll(PDO::FETCH_ASSOC))
            {
                    for($i =0; $i < count($response); $i++)
                    {
                        $req2 = $this->_pdo->prepare("SELECT (SELECT GROUP_CONCAT(c1.id_categorie) FROM categorie c1 LEFT JOIN appartient ON c1.id_categorie = appartient.id_categorie WHERE appartient.id_pwit = ?) as idCat,
                                                  ( SELECT GROUP_CONCAT(c2.cat_titre) FROM categorie c2 LEFT JOIN appartient ON c2.id_categorie = appartient.id_categorie WHERE appartient.id_pwit = ?) as titleCat FROM pwit WHERE pwit.id_pwit = ?");
                        $req2->execute(array($response[$i]['id_pwit'], $response[$i]['id_pwit'], $response[$i]['id_pwit']));
                        $response2 = $req2->fetchAll(PDO::FETCH_ASSOC);
                        $response[$i]["catList"] = $response2;
                    }

                return $response;

            }
            else
            {
                return false;
            }
        }
        catch( Exception $e)
        {
            echo $e->getMessage();
        }
    }


    /**
     * add user sub to tag info in db
     * @param $cat
     * @param $user
     * @return bool
     */
    public function subToCat($cat,$user)
    {
        try
        {
            if( $req = $this->_pdo->prepare("INSERT INTO abonner(id_categorie, id_utilisateur) VALUES(?,?)"))
            {
                if($req->execute(array($cat,$user)))
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

    /**
     * delete user sub to tag info in db
     * @param $cat
     * @param $user
     * @return bool
     */
    public function unSubToCat($cat,$user)
    {
        try
        {
           if( $req = $this->_pdo->prepare("DELETE FROM abonner WHERE id_categorie = ? AND id_utilisateur = ?"))
           {
               if($req->execute(array($cat,$user)))
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

    /**
     * get all tag id subscribe by user in db
     * @param $user
     * @return array|bool
     */
    public function getSubInfo($user)
    {
        try
        {
            if( $req = $this->_pdo->prepare("SELECT abonner.id_categorie, cat_titre FROM abonner LEFT JOIN categorie ON abonner.id_categorie = categorie.id_categorie WHERE id_utilisateur = ?"))
            {
                if( $req->execute(array($user)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
                {

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


}