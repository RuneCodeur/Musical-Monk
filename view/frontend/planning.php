<?php $title = 'Musical-Monk';
ob_start();

if(isset($_GET['win'])){
    if($_GET['win'] == 'eventCreated'){
        echo '<div class="win"> Votre évènement à été ajouté à la liste ! </div>';
    }elseif($_GET['win'] == 'registration'){
        echo '<div class="win"> Vous avez été correctement enregistré pour cet évènement ! </div>';
    }
}
if(isset($_GET['err'])){
    if($_GET['err'] == 'connection'){
        echo '<div class="err"> Vous avez été déconecté de votre session. </div>';
    }elseif($_GET['err'] == 'badevent'){
        echo '<div class="err"> Cet evenement n\'existe plus. </div>';
    }elseif($_GET['err'] == 'registred'){
        echo '<div class="err"> vous êtes déja enregistré pour cet évènement. </div>';
    }elseif($_GET['err'] == 'baduser'){
        echo '<div class="err"> vous n\'êtes pas celui que vous prétendez être. </div>';
    }elseif($_GET['err'] == 'noplace'){
        echo '<div class="err"> Désolé, mais il n\'y as pas assez de place pour vous pour cet évènement. </div>';
    }
    else{
        echo '<div class="err"> une erreur est survenu, veuillez réessayer plus tard. </div>';
    }
}
require_once ('view/backend/connectDB.php');

if (isset($_GET['eventpage'])){
    if($_GET['eventpage']<1){
        $eventpage = 1;
    }else{
        $eventpage = $_GET['eventpage'];
    }
}else{
    $eventpage = 1;
}

$req = $bdd->prepare('SELECT id FROM events WHERE date > NOW() ORDER BY date LIMIT 1 OFFSET :eventpage');
$req->bindValue('eventpage', ($eventpage*10) + 1, PDO::PARAM_INT);
$req ->execute();
if ($response = $req->fetch()){
    $nextpage = 1;
}else{
    $nextpage = 0;
}

$req = $bdd->prepare('SELECT events.id as id, events.name as name, users.pseudo as creator, events.registration as registration, events.max_registration as max_registration, events.date as date FROM events INNER JOIN users ON events.creator = users.id WHERE date > NOW() ORDER BY date LIMIT 10 OFFSET :eventpage');
$req->bindValue('eventpage', ($eventpage*10) - 10, PDO::PARAM_INT);
$req ->execute();
$response = $req->fetchAll();
?>

<div class="page planning">
    <h1> évènements </h1>
        
    <?php if(!empty($_SESSION['auth']['id'])){
        echo '<a href="index.php?page=addevent">Je crée mon évènement</a>';
    }
    ?>

    <div class="event-list">

        <?php
        if(COUNT($response) > 0){
        foreach($response as $event){
            $date = explode(' ', $event['date']);
            $hour = explode(':', $date[1]);
            {
            ?>
            
                <a class="cardevent" href="index.php?page=event&id=<?= $event['id']?>">
                    <h2> <?= $event['name'] ?> </h2>
                    <div>
                        <p>organisé par <?= $event['creator'] ?></p>
                        <p>le <?= $date[0] ?> à <?= $hour[0].':'.$hour[1]?> </p>
                        <p>participants : <?= $event['registration'] ?> / <?= $event['max_registration'] ?></p>

                    </div>
                </a>

            <?php
            }}}else{
                echo '<div class="warn"> Désolé, mais il n\'y as pas d\'évenement prévu pour le moment. </div>';
            }
        ?>

    </div>
    <?php
    if($eventpage > 1){
        echo '<a href="index.php?page=planning&eventpage='. ($eventpage -1) .'"> page précédente</>';
    }
    if($nextpage == 1){
        echo '<a href="index.php?page=planning&eventpage='. ($eventpage +1) .'"> page suivante</>';
    }
    ?>

</div>

<?php
$content = ob_get_clean();
require('view/template.php');