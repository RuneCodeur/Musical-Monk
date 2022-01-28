<?php
//penser à faire les try and catch
$title = 'Musical-Monk';

//models
include_once('model/event.php');
$event = new Event();

$listEvent = $event -> AllEvent();
$page = $event -> ActualPage();

//view
include_once('view/user/research-event.php');

$content = ob_get_clean();

include_once('template.php');