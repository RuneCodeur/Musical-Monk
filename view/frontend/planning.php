<?php $title = 'Musical-Monk';
ob_start();

if(isset($_GET['win'])){
    echo '<div class="win"> Votre évènement à été ajouté à la liste ! </div>';
}

?>

<div class="page planning">
    <h1> évènements </h1>
        
    <a href="index.php?page=addevent">Je crée mon évènement</a>

    <div class="event-list">

        <?php
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
            echo
            $nextpage = 1;
        }else{
            $nextpage = 0;
        }

        $req = $bdd->prepare('SELECT events.id as id, events.name as name, users.pseudo as creator, events.registration as registration, events.max_registration as max_registration, events.date as date FROM events INNER JOIN users ON events.creator = users.id WHERE date > NOW() ORDER BY date LIMIT 10 OFFSET :eventpage');
        $req->bindValue('eventpage', ($eventpage*10) - 10, PDO::PARAM_INT);
        $req ->execute();

        while($response = $req->fetch()){
            $date = explode(' ', $response['date']);
            $hour = explode(':', $date[1]);
            {
            ?>
            
                <a class="cardevent" href="index.php?page=event&id=<?= $response['id']?>">
                    <h2> <?= $response['name'] ?> </h2>
                    <div>
                        <p>organisé par <?= $response['creator'] ?></p>
                        <p>le <?= $date[0] ?> à <?= $hour[0].':'.$hour[1]?> </p>
                        <p>participants : <?= $response['registration'] ?> / <?= $response['max_registration'] ?></p>

                    </div>
                </a>

            <?php
            }}
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