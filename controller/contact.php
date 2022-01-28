<?php //fini
$title = 'Musical-Monk';

//view
include_once('view/user/contact.php');

$content = ob_get_clean();

include_once('template.php');