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
    <a href="view/backend/sessionDestroy.php">me deconnecter</a>
    <h1>Mon compte personnel</h1>

    <div class="myaccount">
        <div>mon pseudo : <?=$_SESSION['auth']['pseudo']?></div>
    </div>

    <div>
        <h2>mes reservations</h2>
        <div class="reserved">
            <div class='product'>
                <h3>produit</h3>

                <?php
                    $req = $bdd->prepare('SELECT product.id as product, product.name as name, product.price as price, reserved_product.id as id, reserved_product.quantity as quantity FROM reserved_product INNER JOIN product ON reserved_product.product = product.id WHERE reserved_product.user = :user ');
                    $req ->execute(array(
                        'user' => $_SESSION['auth']['id']
                    ));
                    while($product = $req->fetch()){
                        ?>

                        <div class="item-product">
                            <a href="index.php?page=product&id=<?=$product['product']?>">
                                <div><?=$product['name']?></div>
                                <div> quantité: <?=$product['quantity']?></div>
                                <div> prix: <?=$product['price'] * $product['quantity']?> €</div>
                            </a>
                            <a href="view/backend/delete-reserve-product.php?deleteproduct=<?=$product['id']?>" class="delete">X</a>
                        </div>
                    
                    <?php
                    } 
                    ?>
            </div>

            <div class='events'>
                <h3>évènements</h3>

                <?php
                    $req = $bdd->prepare('SELECT events.id as event, reserved.id as id, reserved.friend as friend, events.name as name, events.date as date FROM reserved INNER JOIN events ON reserved.event = events.id WHERE reserved.user = :user AND date > NOW() ORDER BY date ');
                    $req ->execute(array(
                        'user' => $_SESSION['auth']['id']
                    ));
                    while($event = $req->fetch()){
                        $date = explode(' ', $event['date']);
                        $hour = explode(':', $date[1]);
                        ?>
                        
                        <div class="item-event">
                            <a href="index.php?page=event&id=<?=$event['event']?>">
                                <div><?=$event['name']?></div>
                                <div>le <?= $date[0] ?> à <?= $hour[0].':'.$hour[1]?></div>
                                <div> <?php if($event['friend'] == 1){echo 'je viens avec un ami';}else{echo 'je viens tout seul';}?></div>
                            </a>
                            <a href="view/backend/delete-reserve-event.php?deleteevent=<?=$event['id']?>" class="delete">X</a>
                        </div>
                    
                    <?php
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