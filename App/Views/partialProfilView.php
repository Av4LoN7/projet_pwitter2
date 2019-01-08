<div style="margin-top:2vh;">
    <?php if($info[0]['u_profil_img'] == null)
        {
            echo '<img style="border-radius:50px; overflow: hidden; height:100px; width: 100px;" src="../../projet_pwitter/avatar/no_avatar/profile.jpg"></div>';
        }
        else
            {
                echo '<img style="border-radius:50px; overflow: hidden; height:100px; width: 100px;" src="'. $info[0]["miniature"]. '" ></div>';
            }
            ?>

<div><?php echo '<a href="-index.php?userID='.$info[0]['id_utilisateur'].'&controller=frontProfil&action=frontPage">'.$info[0]['u_identifiant'].'
</a>';?></div>
<h4>Description</h4>
<div class="profildescription"> <?php echo $info[0]['u_profil_description']; ?></div><hr>
</div>
