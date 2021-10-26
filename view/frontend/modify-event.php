<?php $title = 'Musical-Monk';
ob_start();

require_once ('view/backend/connectDB.php');

if(!empty($_POST)){
    include('view/backend/modify-event.php');
} 

if(isset($_GET['id'])){
    $req = $bdd->prepare('SELECT * FROM events WHERE id= :id ');
    $req ->execute(array(
        'id' => $_GET['id']
    ));
    $event = $req->fetch();
}else{
    header('location: index.php?page=planning&err=badevent');
    die;
}

if(empty($event) || $event['creator'] != $_SESSION['auth']['id']){
    session_unset();
    session_destroy();
    header('location: index.php?err=baduser');
    die;
}

$date = explode(' ', $event['date']);
?>

<div class="page addevent">
    <h1>Modifier mon évènement</h1>

    <?php if (!empty($errorsCreate)){
        ?>

        <div class="err">
            <p>Vous n'avez pas rempli le formulaire correctement :</p>
            <ul>

            <?php 
            foreach($errorsCreate as $error){
                echo '<li>'. $error . '</li>';
            }?>

            </ul>
        </div>

    <?php
    }
    ?>

    <form method="post" action="index.php?page=modifyevent&id=<?=$event['id']?>">
        <fieldset>
            <div class="thinInput">
                <label for="name"> Nom  :</label>
                <input type="text" id="name" name="name" placeholder="mon évènement" required value="<?=$event['name']?>">
            </div>

            <div class="thinInput">
                <label for="time"> Heure :</label>
                <input type="time" id="time" name="time" required value="<?=$date[1]?>">
            </div>

            <div class="thinInput">
                <label for="date"> Date :</label>
                <input type="date" id="date" name="date" required value="<?=$date[0]?>">
            </div>

            <div class="thinInput">
                <label for="duration"> Durée :</br> (min 15 minutes et max 5 heures)</label>
                <input type="time" max="15:00" min="00:00" id="duration" name="duration" required value="<?=$event['duration']?>">
            </div>

            <div class="largeInput">
                <label for="description"> Description de l'évènement :</label>
                <textarea id="description" name="description" row="2" col="5" placeholder="ma description" required ><?=$event['description']?></textarea>
            </div>

            <input class="submit button-style" type="submit" value="modifier mon évènement">
        </fieldset>
    </form>
</div>

<?php
$content = ob_get_clean();
require('view/template.php');