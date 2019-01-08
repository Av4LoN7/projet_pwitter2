<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/Style/pwitterConnexion.css">
    <script type="text/javascript" src="Assets/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="Assets/jsAccueiljs.js"></script>
    <title>Page d'acceuil</title>
</head>

<body>
    <h3>Connection </h3>
    <form class="connectForm" method="POST" action="-index.php?action=connect&controller=auth">
        <div class="connexion form-group">
            <label for="email"> Email</label>
            <input class="form-control" name="email" type="email" placeholder="email" required>
            <label for="password"> Mot de passe </label>
            <input class="form-control" name="password" type="text" placeholder="mot de passe" required>
        <input class="submitConnect btn btn-primary" type="submit" value="valider">
        </div>
    </form>
<div style="text-align: center;">
    <?php if(isset($error))
        {
            echo $error;
        }
        if(isset($response) && is_array($response))
        {
            echo $response["error"];
        }
        elseif(isset($response))
        {
            echo $response;
        }
        ?>
<button class="subscribe btn">Pas encore inscrit ? </button>
</div>
    <div id="formSub">
    <form class="subscription">
        <div class="inscription form-group">
    <label for="nom">Nom </label>
        <input class="form-control" type="text" name="nom">
    <label for="prenom">Prenom </label>
        <input class="form-control" type="text" name="prenom">
    <label>Date de naissance</label>
        <input class="form-control" type="date" name="birthday">
   <label>Pseudo</label>
        <input class="form-control" type="text" name="pseudo">
    <label>Email</label>
        <input class="form-control" type="email" name="email">
   <label>Mot de passe</label>
        <input class="form-control" type="password" name="password">
        <input class="submitSub btn btn-primary" type="submit" value="valider">
        </div>
    </form>
    </div>
</body>
</html>
