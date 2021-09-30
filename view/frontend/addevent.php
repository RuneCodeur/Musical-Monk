<?php $title = 'Musical-Monk';
ob_start();

if(!empty($_POST)){
    include('view/backend/addevent.php');
} 

?>

<div class="page addevent">
    <h1> ajouter un événement </h1>

    <?php if (!empty($errorsCreate)){
        ?>

        <div class="err">
            <p>vous n'avez pas rempli le formulaire correctement :</p>
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
                <label for="name"> nom  :</label>
                <input type="text" id="name" name="name" placeholder="mon évènement" required>
            </div>

            <div class="thinInput">
                <label for="time"> heure :</label>
                <input type="time" id="time" name="time" required>
            </div>

            <div class="thinInput">
                <label for="date"> date :</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="thinInput">
                <label for="duration"> durée :</br> (min 15 minutes et max 5 heures)</label>
                <input type="time" max="15:00" min="00:00" id="duration" name="duration" required>
            </div>

            <div class="thinInput">
                <label for="maxRegistration">nombre maximum de participants : </br> (50 max)</label>
                <input type="number" min="5" max="50" id="maxRegistration" name="maxRegistration" value="5" required>
            </div>

            <div class="largeInput">
                <label for="description"> description de l'évènement :</label>
                <textarea id="description" name="description" row="2" col="5" placeholder="ma description" required></textarea>
            </div>

            <input class="submit" type="submit" value="créer mon évènement">
        </fieldset>
    </form>

</div>

<?php
$content = ob_get_clean();
require('view/template.php');