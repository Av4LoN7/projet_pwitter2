<?php

class searchModels extends coreModel
{
    /**
     * get all person like search input
     * @param $term
     * @return bool
     */
    public function research($term)
    {
        try
        {
            $req = $this->_pdo->prepare("SELECT id_utilisateur, u_identifiant, u_profil_img, u_nom, u_prenom 
                                        FROM utilisateur 
                                        WHERE u_identifiant LIKE :term OR u_nom LIKE :term  OR u_prenom LIKE :term");
            if($req->bindValue(':term', $term.'%') && $req->execute())
            {
                if($response['search'] = $req->fetchAll(PDO::FETCH_ASSOC))
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
     * get all tag like search input
     * @param $term
     * @return mixed
     */
    public function researchCat($term)
    {

        try
        {
            $req = $this->_pdo->prepare(
                "SELECT id_categorie, cat_titre FROM categorie WHERE cat_titre LIKE :term ");
            if( $req->bindValue(':term', $term.'%') && $req->execute())
            {
                if($response['searchCat'] = $req->fetchAll(PDO::FETCH_ASSOC))
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
}