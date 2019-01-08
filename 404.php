<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<h1>Erreur 404 </h1>

<?php
if(isset($error))
{
    echo '<div class="alert alert-danger">'. $error. '</div>';
}
?>

</body>
</html>

