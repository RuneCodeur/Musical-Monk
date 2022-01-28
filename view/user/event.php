<div class="page event">
    <h1><?= $infoEvent['name']?></h1>
    
    <?php
    if(isset($_SESSION['auth'])){
        if($infoEvent['idcreator'] == $_SESSION['auth']['id']){
            echo '<a href="index.php?page=modifyevent&id='. $infoEvent['id'] .'">Modifier mon évènement</a>';
        }
    }
    ?>

    <div>
        <p class="proposed">Cet évènement est proposé par <?= $infoEvent['creator']?>
        <br><?= 'Prévu pour le ' . $date['day'][2] . '/' . $date['day'][1] . '/' . $date['day'][0] . ' à ' . $date['hour'][0] . ':' . $date['hour'][1]. '.'?>
        </p>
        
        <?php
        if($duration[0] > 0){
            echo '<p> Durée prévue : '.$duration[0].' heure(s) et '.$duration[1].' minutes.</p>';
        }else{
            echo '<p> Durée prévue : '.$duration[1].' minutes.</p>';
        }
        ?>

        <p class="descri"><?= $infoEvent['description']?></p>
        <h2 class="reserved">Participants : <?= $infoEvent['registration'] ?> / <?= $infoEvent['max_registration'] ?></h2> 
        <ul>

            <?php
            $iHaveReserved = 0;
            if($reservation != null){
                foreach ($reservation as $reserved){
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
            }
            ?>

        </ul>
    </div>

    <?php
    if(!isset($_SESSION['auth'])){
        echo "<div class='warn'>Vous devez vous connecter pour vous inscrire.</div> ";
    }
    elseif($iHaveReserved != 0){
        echo "<div class='warn'> Vous vous êtes déjà enregistré pour cet évènement.</div> ";
    }
    elseif($infoEvent['registration'] >= $infoEvent['max_registration']){
        echo "<div class='warn'> cet évènement affiche complet !</div> ";
    }
    else{
    ?>

        <form method="post" action="index.php?page=event&reservation=true&id=<?=$_GET['id']?>">
            <fieldset>

                <?php
                if(($infoEvent['registration']+2) <= $infoEvent['max_registration']){
                    echo '<div> <input type="checkbox" name="friend" id="friend"><label for="friend">Je viens avec un pote</label> </div>';
                }
                ?>

                <input type="submit" value="je m'inscrit à cet évènement !" class="input-style">
            </fieldset>
        </form>

    <?php
    }
    ?>
</div>
