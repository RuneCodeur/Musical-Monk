<?php $title = 'Musical-Monk';
ob_start();
?>

<div class="page">
    ajout d'un produit RESERVE AUX ADMINS <br>

    photo 1 2 3 <br>
    nom du produit <br>
    type de produit <br>
    mot clés <br>
    description <br>
    quantité <br>
    prix
</div>

<?php
$content = ob_get_clean();
require('view/template.php');