<?php $title = 'Musical-Monk';

ob_start();

?>

<div class="page">
    pour s'inscrire à un evenement <br>
    nom de l'organisateur <br>
    nombre de place de libre <br>
    description de l'evenement <br>
    je viens tout seule ou avec un pote <br>
</div>

<?php
$content = ob_get_clean();

require('view/template.php');