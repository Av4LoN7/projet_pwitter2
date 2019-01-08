<?php
include_once('../'.COREPATH.'SPDO.php');
$_pdo = SPDO::getInstance()->getPDO();

$term = $_GET['term'];

$req = $_pdo->prepare("SELECT id_utilisateur, identifiant, email
                      FROM utilisateur WHERE identifiant LIKE :term% 
                      OR email LIKE :term% 
                      OR nom LIKE :term%
                      OR prenom LIKE :term%");
$req->bindValue('term', $term);
$req->execute();

while($response = $req->fetch())
{
    echo json_encode($response);
}

?>