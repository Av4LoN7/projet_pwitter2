<?php

class subModels extends coreModel
{
    /**
     * user registeration in db
     * @param array $data
     * @return bool
     */
    public function newUserSubs(array $data)
    {
      if( $this->verifyUserMail($data['email']) == false)
        {
            if($this->verifyIdentifiant($data['pseudo']) == false)
            {
                try
                {
                    $req = $this->_pdo->prepare(
                        'INSERT INTO utilisateur (u_email, u_nom, u_prenom, u_date_de_naissance, u_identifiant, u_mot_de_passe, u_date_inscription, u_derniere_connection, u_profil_description, u_profil_img, u_est_connecte, id_role) 
                  VALUES (:mail, :nom, :prenom, :birth, :pseudo, :password, NOW(), NOW(), null, null, 0,1)');
                    $req->bindValue( 'nom', $data['nom']);
                    $req->bindValue( 'mail', $data['email']);
                    $req->bindValue( 'prenom', $data['prenom']);
                    $req->bindValue( 'birth', $data['birthday']);
                    $req->bindValue( 'pseudo', $data['pseudo']);
                    $req->bindValue( 'password', $data['password']);

                    if($req->execute())
                    {
                        return true;
                    }
                }
                catch(Exception $e)
                {
                    echo $e->getMessage();
                }
            }
            else
            {
                $error['error'] = "Ce speudo est déjà pris !";
                return $error;
            }
        }
        else
        {
            $error['error'] = "Ce mail est deja enregister dans notre basse de donnèes !";
            return $error;
        }
    }

    /**
     * verify if user email allready exist in db
     * @param $mail
     * @return bool
     */
    private function verifyUserMail($mail)
    {
        try
        {
            if( $req = $this->_pdo->prepare('SELECT * FROM utilisateur WHERE u_email = ?'))
            {
                if( $req->execute(array($mail)) && $response = $req->fetch(PDO::FETCH_ASSOC))
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

    private function verifyIdentifiant($ident)
    {
        try
        {
            if( $req = $this->_pdo->prepare('SELECT * FROM utilisateur WHERE u_identifiant = ?'))
            {
                if( $req->execute(array($ident)) && $response = $req->fetch(PDO::FETCH_ASSOC))
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
}