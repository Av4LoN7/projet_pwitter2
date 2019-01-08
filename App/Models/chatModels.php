<?php

class chatModels extends coreModel
{
    /**
     * get all tchat message between user and another from db
     * @param $idUser
     * @param $idAbo
     * @return array|bool
     */
    public function getMessage($idUser, $idAbo)
    {
        try
        {
            $key = $idUser.','.$idAbo;
            $query = "SELECT message.id_message, m_message, m_date_message, (message.id_utilisateur) as sender, u_profil_img, u_identifiant 
                      FROM message 
                      LEFT JOIN destiner ON message.id_message = destiner.id_message 
                      LEFT JOIN utilisateur ON message.id_utilisateur = utilisateur.id_utilisateur 
                      WHERE message.id_utilisateur IN($key) AND destiner.id_utilisateur IN ($key) 
                      ORDER BY message.id_message ASC";
            if($req = $this->_pdo->prepare($query) )
            {
                if($req->execute() && $response = $req->fetchAll(PDO::FETCH_ASSOC))
                {
                    return $response;
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
     * save new user input message tchat in db
     * @param $user
     * @param $abo
     * @param $data
     * @param int $isFriend
     * @return bool
     */
    public function insertMessage($user, $abo, $data)
    {
        try
        {
            $req = $this->_pdo->prepare("INSERT INTO message (id_utilisateur, m_message) VALUES(?,?)");

            if($req->execute(array($user, $data)))
            {
                $lastID = $this->_pdo->lastInsertId();

                $req2 = $this->_pdo->prepare("INSERT INTO destiner(id_message, id_utilisateur) VALUES(?,?)");

                if($req2->execute(array($lastID, $abo)))
                {
                   return true;
                }
                return false;
            }
            return false;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    private function isFollow($user, $other)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT * FROM suivre WHERE id_utilisateur = ? AND id_utilisateur_1 = ? ");
            if($req->execute(array($other, $user)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
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
     * get last tchat message user in db
     * @param $user
     * @param $abo
     * @param $last
     * @return array|bool
     */
    public function getLastMess($user,$abo, $last)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT message.m_message, message.id_message, m_date_message, message.id_utilisateur FROM message LEFT JOIN destiner ON destiner.id_message = message.id_message WHERE message.id_utilisateur IN (:user1,:abo) AND destiner.id_utilisateur IN (:user1,:abo) AND message.id_message >:idconv ORDER BY message.m_date_message DESC");
            $req->bindValue("user1", $user);
            $req->bindValue("abo", $abo);
            $req->bindValue("idconv", $last);
            if($req->execute() && $response = $req->fetchAll(PDO::FETCH_ASSOC))
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

    public function getAllPrivateMessage($userID)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT COUNT(message.id_message) as nombreMess, message.id_utilisateur, u_identifiant, u_profil_img 
                                          FROM utilisateur 
                                          LEFT JOIN message ON message.id_utilisateur = utilisateur.id_utilisateur 
                                          LEFT JOIN destiner ON destiner.id_message = message.id_message 
                                          WHERE destiner.id_utilisateur = ? 
                                          GROUP BY utilisateur.id_utilisateur");
            if($req->execute(array($userID)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
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