<?php

if(!isset($_SESSION['auth']['id'])){
    header('location: index.php?page=planning&err=connection');
    die;
}

if(isset($_GET['id'])){
    $req = $bdd->prepare('SELECT creator, registration FROM events WHERE id= :id ');
    $req ->execute(array(
        'id' => $_GET['id']
    ));
    $event = $req->fetch();
    if($event['creator'] != $_SESSION['auth']['id']){
        session_unset();
        session_destroy();
        header('location: index.php?page=planning&err=baduser');
        die;
    }
}else{
    header('location: index.php?page=planning&err=badevent');
    die;
}

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
    $errorsCreate['duration'] = 'vous ne pouvez pas creer un évènement qui dure plus de 5 heures.';
}

if(empty($_POST['description'])){
    $errorsCreate['description'] = 'vous n\'avez pas donnée de description à votre évènement.';
}


if(empty($errorsCreate)){
    $req = $bdd->prepare('UPDATE events SET name =:name, description =:description, date =:date, duration =:duration WHERE id = :id');
    $req->execute(array(
        'name' => strip_tags($_POST['name']),
        'description' => strip_tags($_POST['description']),
        'date' => strip_tags($_POST['date']) . ' ' . strip_tags($_POST['time']),
        'duration' => strip_tags($_POST['duration']),
        'id' => $_GET['id']
    ));

    header('location: index.php?page=planning&win=eventmodified');
}
