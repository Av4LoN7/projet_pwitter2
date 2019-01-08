<?php

class authModels extends coreModel
{
    /**
     * verify user email and password for connection
     * @param $mail
     * @param $pass
     * @return bool|mixed
     */
    public function connectUserAction($mail, $pass)
    {

        if($mail && $pass != null)
        {
            try
            {
                $req = $this->_pdo->prepare('SELECT * FROM utilisateur WHERE u_email = ?');
                if( $req->execute(array($mail)) && $response = $req->fetch(PDO::FETCH_ASSOC))
                {
                    if( password_verify($pass, $response['u_mot_de_passe']) != false )
                    {
                        if($req2 = $this->_pdo->prepare("UPDATE utilisateur SET u_derniere_connection = NOW(), u_est_connecte = true 
                                                        WHERE id_utilisateur = ?"))
                        {
                            if($req2->execute(array($response['id_utilisateur'])))
                            {
                                unset($response['u_mot_de_passe']);

                                return $response;
                            }
                        }
                        return false;
                    }
                    else
                    {
                        return $error["error"] = "Erreur votre mots de passe est incorrect";
                    }
                }
                else
                {
                    return $error["error"] = "Erreur votre email n'existe pas dans notre base de donnée";
                }

            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * update user is_connected statut
     * @param $user
     * @return bool
     */
    public function onlineOff($user)
    {
        try
        {
            $req = $this->_pdo->prepare("UPDATE utilisateur SET u_est_connecte = false WHERE id_utilisateur = ?");
            if($req->execute(array($user)))
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
}