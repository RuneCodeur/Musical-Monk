<?php $title = 'Musical-Monk';
ob_start();
?>
<div class="page contact">
    <p>presentation rapide du site</p>
    <p>barre de recherche</p>
    <p>prochain evenement</p>

</div>
    
<?php
$content = ob_get_clean();
require('view/template.php');