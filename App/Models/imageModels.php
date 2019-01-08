<?php

class imageModels
{
    private $_pdo;

    public function __construct()
    {
        $this->_pdo = SPDO::getInstance()->getPDO();
    }

    public function addImageTo($path, $user, $from, $lastID = null)
    {
        switch ($from)
        {
            case "profil":
                $result = $this->addImageToProfil($path,$user);
                return $result;
                break;
            case "pwit":
                $result = $this->addImageToPwit($path,$lastID, $user);
                return $result;
                break;
        }

    }

    public function dropAvatar($user)
    {
        try
        {
            $req = $this->_pdo->prepare("UPDATE utilisateur SET profil_img = NULL WHERE id_utilisateur = ?");
            if($req->execute(array($user)))
            {
                return true;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }



    /*public function addImageToPwit($file, $ID, $user)
    {
        try
        {
            $req = $this->_pdo->prepare("UPDATE pwit SET pwit_img = ? WHERE id_pwit = ?");
            if($req->execute(array($file,$ID)))
            {
                $pwit = new pwittModels();

                if($response = $pwit->lastPwittUser($user))
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
    */
}