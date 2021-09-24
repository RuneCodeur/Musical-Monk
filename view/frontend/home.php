<?php $title = 'Musical-Monk';
ob_start();
if(isset($_GET['win'])){
    if($_GET['win'] == 'addproduct'){
        echo '<div class="win"> Le produit à bien été rajouté. </div>';
    }
}
if(isset($_GET['err'])){
    if($_GET['err'] == 'unauthorized'){
        echo '<div class="err"> Vous n\'êtes pas authorisé à vous rendre ici. </div>';
    }elseif($_GET['err'] == 'disconnect'){
        echo '<div class="err"> Vous avez été déconecté de votre session. </div>';
    }
    else{
        echo '<div class="err"> une erreur est survenu, veuillez réessayer plus tard. </div>';
    }
}
?>

<div class="page contact">
    <p>presentation rapide du site</p>
    <p>barre de recherche</p>
    <p>prochain evenement</p>

</div>
    
<?php
$content = ob_get_clean();
require('view/template.php');