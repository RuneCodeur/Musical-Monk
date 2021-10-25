<?php $title = 'Musical-Monk';
ob_start();

if(!empty($_POST)){
    include('view/backend/create-event.php');
} 

?>

<div class="page addevent">
    <h1>Ajouter un événement</h1>

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

    <form method="post" action="index.php?page=addevent">
        <fieldset>
            <div class="thinInput">
                <label for="name">Nom  :</label>
                <input type="text" id="name" name="name" placeholder="mon évènement" required>
            </div>

            <div class="thinInput">
                <label for="time">Heure :</label>
                <input type="time" id="time" name="time" required>
            </div>

            <div class="thinInput">
                <label for="date">Date :</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="thinInput">
                <label for="duration">Durée :</br> (min 15 minutes et max 5 heures)</label>
                <input type="time" max="15:00" min="00:00" id="duration" name="duration" required>
            </div>

            <div class="thinInput">
                <label for="maxRegistration">Nombre maximum de participants : </br> (50 max)</label>
                <input type="number" min="5" max="50" id="maxRegistration" name="maxRegistration" value="5" required>
            </div>

            <div class="largeInput">
                <label for="description">Description de l'évènement :</label>
                <textarea id="description" name="description" row="2" col="5" placeholder="ma description" required></textarea>
            </div>

            <input class="submit" type="submit" value="créer mon évènement">
        </fieldset>
    </form>

</div>

<?php
$content = ob_get_clean();
require('view/template.php');