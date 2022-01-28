<div class="page planning">
    <h1> évènements </h1>

    <?php
    if(!empty($_SESSION['auth']['id'])){
        echo '<a href="index.php?page=addevent">Je crée mon évènement</a>';
    }
    ?>

    <div class="event-list">

        <?php
        if($listEvent != null){
            foreach($listEvent as $event){
                $date = explode(' ', $event['date']);
                $day = explode('-', $date[0]);
                $hour = explode(':', $date[1]);
                {
                ?>
                    <a class="cardevent" href="index.php?page=event&id=<?= $event['id']?>">
                        <h2> <?= $event['name'] ?> </h2>
                        <div>
                            <p>Organisé par <?= $event['creator'] ?></p>
                            <p>Le <?= $day[2]?>/<?= $day[1]?>/<?= $day[0]?> à <?= $hour[0].':'.$hour[1]?> </p>
                            <p>Participants : <?= $event['registration'] ?> / <?= $event['max_registration'] ?></p>

                        </div>
                    </a>

                <?php
                }
            }
        }else{
            echo '<div class="warn"> Désolé, mais il n\'y as pas d\'évenement prévu pour le moment. </div>';
        }
        ?>

    </div>

    <?php
    if($page['page'] >1){
        echo '<a href="index.php?page=planning&eventpage='. ($page['page'] -1) .'"> page précédente</a>';
    }
    if($page['next'] === true){
        
        echo '<a href="index.php?page=planning&eventpage='. ($page['page'] +1) .'"> page suivante</a>';
    }
    ?>

</div>