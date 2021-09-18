<?php $title = 'Musical-Monk';
ob_start();
?>

<a href="view/backend/sessionDestroy.php">deconexion</a>
<div class="page">
    mon compte
    nom du compte <br>
    produit reservé <br>
    mes evenements <br>
</div>
    
<?php
$content = ob_get_clean();
require('view/template.php');