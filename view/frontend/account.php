<?php $title = 'Musical-Monk';
ob_start();


if(!isset($_SESSION['auth'])){
    session_destroy();
    header('location: index.php?err=connexion');
    die;
}

if(isset($_GET['modify']) && !empty($_POST)){
    include('view/backend/modify-account.php');
}

require_once ('view/backend/connectDB.php');

$req = $bdd->prepare('SELECT pseudo FROM users WHERE id= :id ');
$req ->execute(array(
    'id' => $_SESSION['auth']['id']
));
$res = $req->fetch();
if(empty($res) || $res['pseudo'] != $_SESSION['auth']['pseudo']){
    session_unset();
    session_destroy();
    header('location: index.php?err=baduser');
    die;
}

if(isset($_GET['win'])){
    if($_GET['win'] == 'validtoken'){
        echo '<div class="win"> votre adresse mail à été validé </div>';
    }
}
?>

<div class="page account">
    <h1>Mon compte personnel</h1>

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

    <div class="all-info">
        <div class="my-account">
            <div>Mon pseudo : <?=$_SESSION['auth']['pseudo']?></div>
            <div>Mon mail : <?=$_SESSION['auth']['mail']?></div>
            <input type="button" value="modifier mes informations" id="button-modify" class="input-style">
            <div id="modify">
                <form method="post" action="index.php?page=account&modify=pseudo">
                    <fieldset>
                        <div>
                            <label for="modify-pseudo">Nouveau pseudo :</label>
                            <input type="text" id="modify-pseudo" name="modify-pseudo" >
                        </div>

                        <input class="submit input-style" type="submit" value="Modifier mon pseudo" >

                    </fieldset>
                </form>
                <form method="post" action="index.php?page=account&modify=mail">
                    <fieldset>
                        <div>
                            <label for="modify-mail">Nouveau mail :</label>
                            <input type="mail" id="modify-mail" name="modify-mail" >
                        </div>

                        <input class="submit input-style" type="submit" value="Modifier mon adresse mail" >

                    </fieldset>
                </form>
                <form method="post" action="index.php?page=account&modify=mdp">
                    <fieldset>
                        <div>
                            <label for="modify-mdp">Nouveau mot de passe :</label>
                            <input type="password" id="modify-mdp" name="modify-mdp" onkeyup="check()">
                        </div>

                        <div>
                            <label for="confirm-modify-mdp">Confirmation du nouveau mot de passe :</label>
                            <input type="password" id="confirm-modify-mdp" name="confirm-modify-mdp" onkeyup="check()" >
                        </div>

                        <input class="submit input-style" type="submit" value="Modifier mon mot de passe" >

                    </fieldset>
                </form>
            </div>
        </div>

        <div class="reserved">
            <h2>Mes reservations</h2>

            <?php
                $req = $bdd->prepare('SELECT product.id as product, product.name as name, product.price as price, reserved_product.id as id, reserved_product.quantity as quantity FROM reserved_product INNER JOIN product ON reserved_product.product = product.id WHERE reserved_product.user = :user ');
                $req ->execute(array(
                    'user' => $_SESSION['auth']['id']
                ));
                $product = $req->fetchall();

                if(!empty($product)){

                    ?>

                        <div class='product'>
                            <h3>Produit</h3>
                            <div class="product-title">
                                <div class="name">Nom du produit</div>
                                <div class="quantity">Quantité</div>
                                <div class="price">Prix</div>
                                <div class="delete"></div>
                             </div>

                    <?php
                    foreach($product as $elem){

                        ?>

                        <div class="item-product">
                            <a href="index.php?page=product&id=<?=$elem['product']?>" class="my-item">
                                <div class="name"><?=$elem['name']?></div>
                                <div class="quantity"> <?=$elem['quantity']?></div>
                                <div class="price"> <?=$elem['price'] * $elem['quantity']?> €</div>
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
                            <a href="index.php?page=event&id=<?=$elem['event']?>" class="my-event">
                                <div class="name-event"><?=$elem['name']?></div>
                                <div class="date">le <?= $day[2]?>/<?= $day[1]?>/<?= $day[0]?> à <?= $hour[0].':'.$hour[1]?></div>
                                <div class="friend"> <?php if($elem['friend'] == 1){echo 'je viens avec un ami';}else{echo 'je viens tout seul';}?></div>
                            </a>
                            <a href="view/backend/delete-reserve-event.php?deleteevent=<?=$elem['id']?>" class="delete">X</a>
                        </div>
                        
                    <?php
                    }
                    echo '</div>' ;
                }

                if(empty($product) & empty($event)){
                    echo '<p>Tu n\'a aucune reservation</p>';
                }
            ?>
        </div>
    </div>

    <div class="leader-event">
        <h3>Les évènements que j'organise</h3>
        <?php
        $req = $bdd->prepare('SELECT name, id, date, max_registration, registration FROM events WHERE creator = :user AND date > NOW() ORDER BY date');
        $req ->execute(array(
            'user' => $_SESSION['auth']['id']
        ));
        while($myevent = $req->fetch()){
            $date = explode(' ', $myevent['date']);
            $day = explode('-', $date[0]);
            $hour = explode(':', $date[1]);
            ?>
        
            <a href="index.php?page=event&id=<?=$myevent['id']?>">
                <div class="name"><?=$myevent['name']?></div>
                <div class="date"><?= $day[2]?>/<?= $day[1]?>/<?= $day[0]?> <br> à <?= $hour[0].':'.$hour[1]?> </div>
                <div class="users">Participants : <?=$myevent['registration']?> / <?=$myevent['max_registration']?> </div>
            </a>
                
        <?php
        }
        ?>
    </div>
</div>

<script src="public/js/modify-account.js"></script>

<?php
$content = ob_get_clean();
require('view/template.php');