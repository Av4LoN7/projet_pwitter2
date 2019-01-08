<?php

class adminModels extends coreModel
{
        public function getAllUserForSupp($admin)
        {
            try
            {
              $req = $this->_pdo->prepare("SELECT id_utilisateur, u_identifiant FROM utilisateur WHERE id_utilisateur <> ?");
              if($req->execute(array($admin)) && $response = $req->fetchAll(PDO::FETCH_ASSOC))
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

        public function deleteUserProfil($user)
        {
            try
            {
                if($this->delMessageUser($user) && $this->delRelation($user))
                {
                    $req = $this->_pdo->prepare("DELETE FROM utilisateur WHERE utilisateur.id_utilisateur = ?");
                    if($req->execute(array($user)))
                    {
                        return true;
                    }
                }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }
        private function delMessageUser($user)
        {
            $req = $this->_pdo->prepare("DELETE FROM destiner WHERE destiner.id_utilisateur = ?");
            if($req->execute(array($user)))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        private function delRelation($user)
        {
            $req = $this->_pdo->prepare("DELETE FROM suivre WHERE suivre.id_utilisateur = ? OR suivre.id_utilisateur_suivre = ?");
            if($req->execute(array($user,$user)))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
}