<?php $title = 'Musical-Monk';
ob_start();
require_once ('view/backend/connectDB.php');

$req = $bdd->prepare('SELECT events.name as name, events.description as description, events.date as date, events.duration as duration, events.registration as registration, events.max_registration as max_registration, users.pseudo as creator FROM events INNER JOIN users ON events.creator = users.id WHERE events.id= :id ');
$req ->execute(array(
    'id' => $_GET['id']
));
$infoevent = $req->fetch();

if($infoevent == null){
    header('location: index.php?page=planning&err=page');
}

$date = explode(' ', $infoevent['date']);
$hour = explode(':', $date[1]);
$hourDuration = explode(':', $infoevent['duration']);
?>

<div class="page event">
    <h1><?= $infoevent['name']?></h1>
    <div>
        <p class="proposed">cet evenement est proposé par <?= $infoevent['creator']?>
        <br> prévu pour le <?= $date[0]?> à <?= $hour[0] . ':' . $hour[1]?>.</p>
        <?php
        if($hourDuration[0] > 0){
            echo '<p> durée prévue : '.$hourDuration[0].' heure(s) et '.$hourDuration[1].' minutes.</p>';
        }else{
            echo '<p> durée prévue : '.$hourDuration[1].' minutes.</p>';
        }
        
        ?>
        <p class="descri"><?= $infoevent['description']?></p>
        <h2 class="reserved">participants : <?= $infoevent['registration'] ?> / <?= $infoevent['max_registration'] ?></h2> 
        <ul>
            <?php
            $req = $bdd->prepare('SELECT users.pseudo as user, reserved.user as userid, reserved.friend FROM reserved INNER JOIN users ON reserved.user = users.id WHERE reserved.event= :id ');
            $req ->execute(array(
                'id' => $_GET['id']
            ));
            $iHaveReserved = 0;
            while($reserved = $req->fetch()){
                if(isset($_SESSION['auth'])){
                    if($reserved['userid'] == $_SESSION['auth']['id']){
                        $iHaveReserved = 1;
                    }
                }
                if($reserved['friend'] == 0){
                    echo '<li>' . $reserved['user'] . '</li>';
                }
                else{
                    echo '<li>' . $reserved['user'] . ' avec un pote </li>';
                }
            }
            ?>
        </ul>
    </div>
    <?php
    if($iHaveReserved != 0){
        echo "<div class='warn'> vous vous êtes déja enregistré pour cet évènement.</div> ";
    }
    elseif(isset($_SESSION['auth'])){
        {
        ?>

        <form method="post" action="view/backend/reserve-event.php?id=<?=$_GET['id']?>">
            <fieldset>
                <div>
                    <input type="checkbox" name="friend"><label for="friend"> je viens avec un pote</label>
                </div>
                <input type="submit" value="je m'inscrit à cet evenement !">
                <div></div>
            </fieldset>
        </form>

        <?php
        }
    }
    else{
        echo "<div class='warn'> vous devez vous connecter pour vous inscrire.</div> ";
    }
        
    ?>
</div>

<?php
$content = ob_get_clean();
require('view/template.php');