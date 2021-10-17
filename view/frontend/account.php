<?php $title = 'Musical-Monk';
ob_start();


if(!isset($_SESSION['auth'])){
    header('location: index.php?page=planning&err=connection');
    die;
}
require_once ('view/backend/connectDB.php');

$req = $bdd->prepare('SELECT pseudo FROM users WHERE id= :id ');
$req ->execute(array(
    'id' => $_SESSION['auth']['id']
));
$user = $req->fetch();
if(empty($user)){
    session_unset();
    session_destroy();
    header('location: index.php?page=planning&err=baduser');
    die;
}
elseif($user['pseudo'] != $_SESSION['auth']['pseudo']){
    session_unset();
    session_destroy();
    header('location: index.php?page=planning&err=baduser');
    die;
}

if(isset($_GET['win'])){
    if($_GET['win'] == 'validtoken'){
        echo '<div class="win"> votre adresse mail à été validé </div>';
    }
}
?>

<div class="page account">
    <a href="view/backend/session-destroy.php">me deconnecter</a>
    <h1>Mon compte personnel</h1>

    <div class="myaccount">
        <div>mon pseudo : <?=$_SESSION['auth']['pseudo']?></div>
    </div>

    <div>
        <h2>mes reservations</h2>
        <div class="reserved">

                <?php
                    $req = $bdd->prepare('SELECT product.id as product, product.name as name, product.price as price, reserved_product.id as id, reserved_product.quantity as quantity FROM reserved_product INNER JOIN product ON reserved_product.product = product.id WHERE reserved_product.user = :user ');
                    $req ->execute(array(
                        'user' => $_SESSION['auth']['id']
                    ));
                    $product = $req->fetchall();

                    if(!empty($product)){

                        ?>

                            <div class='product'>
                            <h3>produit</h3>

                        <?php
                        foreach($product as $elem){

                            ?>

                            <div class="item-product">
                                <a href="index.php?page=product&id=<?=$elem['product']?>">
                                    <div><?=$elem['name']?></div>
                                    <div> quantité: <?=$elem['quantity']?></div>
                                    <div> prix: <?=$elem['price'] * $elem['quantity']?> €</div>
                                </a>
                                <a href="view/backend/delete-reserve-product.php?deleteproduct=<?=$elem['id']?>" class="delete">X</a>
                            </div>

                            <?php
                        }
                        echo '</div>' ;
                    }



                    $req = $bdd->prepare('SELECT events.id as event, reserved.id as id, reserved.friend as friend, events.name as name, events.date as date FROM reserved INNER JOIN events ON reserved.event = events.id WHERE reserved.user = :user AND date > NOW() ORDER BY date ');
                    $req ->execute(array(
                        'user' => $_SESSION['auth']['id']
                    ));
                    $event = $req ->fetchall();
                    if (!empty($event)){

                        ?>
                            <div class='events'>
                            <h3>évènements</h3>
            
                        <?php

                        foreach($event as $elem){
                            $date = explode(' ', $elem['date']);
                            $day = explode('-', $date[0]);
                            $hour = explode(':', $date[1]);

                            ?>
                            
                            <div class="item-event">
                                <a href="index.php?page=event&id=<?=$elem['event']?>">
                                    <div><?=$elem['name']?></div>
                                    <div>le <?= $day[2]?>/<?= $day[1]?>/<?= $day[0]?> à <?= $hour[0].':'.$hour[1]?></div>
                                    <div> <?php if($elem['friend'] == 1){echo 'je viens avec un ami';}else{echo 'je viens tout seul';}?></div>
                                </a>
                                <a href="view/backend/delete-reserve-event.php?deleteevent=<?=$elem['id']?>" class="delete">X</a>
                            </div>
                        
                        <?php
                        }
                    }

                    if(empty($product) & empty($event)){
                        echo '<p>Tu n\'a aucune reservation</p>';
                    }
                    ?>
            </div>
        </div>

        <div class="leader-event">
                <h3> les evenements que j'organise</h3>
                <?php
                $req = $bdd->prepare('SELECT name, id, date, max_registration, registration FROM events WHERE creator = :user AND date > NOW() ORDER BY date');
                $req ->execute(array(
                    'user' => $_SESSION['auth']['id']
                ));
                while($myevent = $req->fetch()){
                    $date = explode(' ', $myevent['date']);
                    $hour = explode(':', $date[1]);
                    ?>
                    
                    <a href="index.php?page=event&id=<?=$myevent['id']?>">
                        <div><?=$myevent['name']?></div>
                        <div>le <?= $date[0] ?> à <?= $hour[0].':'.$hour[1]?> </div>
                        <div> participants : <?=$myevent['registration']?> / <?=$myevent['max_registration']?> </div>
                    </a>
                
                <?php
                }
                ?>
        </div>

    </div>
</div>

<?php
$content = ob_get_clean();
require('view/template.php');