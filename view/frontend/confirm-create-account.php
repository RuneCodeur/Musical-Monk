<?php $title = 'Musical-Monk';
ob_start();
?>

<div class="page">
    
<div class='win'>Un mail de confirmation à été envoyé dans votre boite mail.</div>

</div>
    
<?php
$content = ob_get_clean();
require('view/template.php');