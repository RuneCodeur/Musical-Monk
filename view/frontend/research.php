<?php $title = 'Musical-Monk';

ob_start();

?>

<div class="page">
    pour voir le resultat de la barre de recherche, comme celui là 
    <a href="index.php?page=product">produit</a>
    <br> <br>
    barre de recherche <br>
    liste des produits <br>
    1 1ere photo <br>
    2 nom du produit <br>
    3 prix <br>
</div>

<?php
$content = ob_get_clean();

include('view/template.php');