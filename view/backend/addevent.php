<?php $title = 'Musical-Monk';

ob_start();?>

<p>
    <?php
    if(!empty($_POST['name']) AND !empty($_POST['date']) AND !empty($_POST['time']) AND !empty($_POST['duration']) AND !empty($_POST['maxRegistration']) AND !empty($_POST['maxRegistration']))
    {
        $name = strip_tags($_POST['name']);
        $date = strip_tags($_POST['date']) . ' ' . strip_tags($_POST['time']);
        $duration = strip_tags($_POST['duration']);
        $maxRegistration = strip_tags($_POST['maxRegistration']);
        $description = strip_tags($_POST['description']);
        $creator = 1;

        $bdd = new PDO('mysql:host=localhost;dbname=musical_monk;charset=utf8;port=3307', 'root', 'root');
        
        $req = $bdd->prepare('INSERT INTO events(name, description, date, duration, creator, max_registration) VALUES(:name, :description, :date, :duration, :creator, :max_registration)');
        
        $req->execute(array(
        'name' => $name,
        'description' => $description,
        'date' => $date,
        'duration' => $duration,
        'creator' => $creator,
        'max_registration' => $maxRegistration
        ));

        {
            ?>

            <p class="win">Votre évenement à été enregistré !</p>

            <?php  
        }
    }
    else
    {
        {
            ?>
            
            <p class="err">Une erreure est survenue, veuillez réessayer plus tard.</p>

            <?php
        }
    }
    ?>
</p>

<script src="public/js/goevent.js"></script>

<?php
$content = ob_get_clean();

require('view/template.php');