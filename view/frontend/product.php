<?php $title = 'Musical-Monk';
ob_start();
?>

<div class="page">
    pour voir un produit en particulier <br>
    photo du produit <br>
    nom du produit <br>
    description du produit <br>
    mot clés <br>
    nombre dispo dans le magasin <br>
    prix <br>
    bouton reserver <br>
</div>

<?php
$content = ob_get_clean();
require('view/template.php');