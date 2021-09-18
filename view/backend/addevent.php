<?php
// à retaper
date_default_timezone_set('Europe/Paris');
require_once ('connectDB.php');

$errorsCreate = array();

if(empty($_POST['name'])){
    $errorsCreate['name'] = 'vous n\'avez pas donnée de nom à votre évènement.';
}

if(empty($_POST['date'])){
    $errorsCreate['date'] = 'vous n\'avez pas donnée de date à votre évènement.';
}elseif(date("Y-m-d") > $_POST['date']){
    $errorsCreate['date'] = 'vous ne pouvez pas creer un évènement dans le passée.';
}elseif((date("Y-m-d") == $_POST['date']) AND date('H:i') > $_POST['time']){
    $errorsCreate['date'] = 'vous ne pouvez pas creer un évènement dans le passée.';
}

if(empty($_POST['time'])){
    $errorsCreate['time'] = 'vous n\'avez pas donnée d\'heure à votre évènement.';
}

if(empty($_POST['duration'])){
    $errorsCreate['duration'] = 'vous n\'avez pas donnée de durée à votre évènement.';
}elseif( '00:15' > $_POST['duration']){
    $errorsCreate['duration'] = 'vous ne pouvez pas creer un évènement qui dure moins de 15 minutes.';
}elseif( '05:00' < $_POST['duration']){
    $errorsCreate['duration'] = 'vous ne pouvez pas creer un évènement qui dureplus de 5 heures.';
}

if(empty($_POST['maxRegistration'])){
    $errorsCreate['maxRegistration'] = 'vous n\'avez pas donnée de durée à votre évènement.';
}elseif( 5 > $_POST['maxRegistration']){
    $errorsCreate['maxRegistration'] = 'vous ne pouvez pas creer un évènement pour moins de 5 personnes.';
}elseif( 50 < $_POST['maxRegistration']){
    $errorsCreate['maxRegistration'] = 'vous ne pouvez pas creer un évènement pour plus de 50 personnes.';
}

if(empty($_POST['description'])){
    $errorsCreate['description'] = 'vous n\'avez pas donnée de description à votre évènement.';
}

if(empty($errorsCreate)){
    $req = $bdd->prepare('INSERT INTO events(name, description, date, duration, creator, max_registration) VALUES(:name, :description, :date, :duration, :creator, :max_registration)');
    $req->execute(array(
    'name' => strip_tags($_POST['name']),
    'description' => strip_tags($_POST['description']),
    'date' => strip_tags($_POST['date']) . ' ' . strip_tags($_POST['time']),
    'duration' => strip_tags($_POST['duration']),
    'creator' => 1,
    'max_registration' => strip_tags($_POST['maxRegistration'])
    ));

    header('location: index.php?page=planning&win=eventCreated');
}