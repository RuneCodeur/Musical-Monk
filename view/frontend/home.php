<?php $title = 'Musical-Monk';
ob_start();
?>

lol
    
<?php
$content = ob_get_clean();
require('view/template.php');