<?php $title = 'Musical-Monk';
ob_start();
require_once ('view/backend/connectDB.php');
if(isset($_GET['win'])){
    if($_GET['win'] == 'addproduct'){
        echo '<div class="win"> Le produit à bien été rajouté. </div>';
    }
}
if(isset($_GET['err'])){
    if($_GET['err'] == 'unauthorized'){
        echo '<div class="err"> Vous n\'êtes pas authorisé à vous rendre ici. </div>';
    }elseif($_GET['err'] == 'disconnect'){
        echo '<div class="err"> Vous avez été déconecté de votre session. </div>';
    }
    else{
        echo '<div class="err"> une erreur est survenu, veuillez réessayer plus tard. </div>';
    }
}
?>

<div class="page home">
    <form action="index.php" method="get" class="research-item">
        <fieldset>
            <div class="param-search">
            <input type="text" name="search" placeholder="rechercher un produit..." class="search-words">
                <div>
                    <input type="radio" name="typesearch" value="0" id="type0" class="search-type" checked>
                    <label for="type0">toutes catégories</label>
                </div>

                <?php
                require_once ('view/backend/connectDB.php');
                $req = $bdd->prepare('SELECT * FROM product_type');
                    $req ->execute();
                    while($listType = $req->fetch()){
                        {?>
                            <div>
                                <input type="radio" name="typesearch" value="<?=$listType['id']?>" id="type<?=$listType['id']?>"  class="search-type">
                                <label for="type<?=$listType['id']?>"><?=$listType['name']?></label>
                            </div>
                        <?php
                        }
                    }
                ?>

            </div>
            <input type="submit" value="Rechercher" class="search-submit">
        </fieldset>
    </form>
    <h1>tout ce qu'il faut pour mettre la musique en oeuvre</h1>
    <div class="presentation">
        <div class="type-product">
            <h2>Des instruments et des accessoires de tout genre</h2>
            <p>un produit aléatoire...</p>

            <?php
            $req = $bdd->prepare('SELECT id, name, price, picture FROM product WHERE quantity > 0 ORDER BY RAND() LIMIT 1');
            $req ->execute();
            $item = $req->fetch();
            if(empty($item)){
                echo '<p> il n\'y a plus de produit à vendre dans la boutique </p>';
            }else{
                ?>
         
                <a href="index.php?page=product&id=<?= $item['id']?>" class="exemple">
                    <img src="<?=$item['picture']?>" alt="<?=$item['name']?>" width="200px">
                    <div>
                        <p><?=$item['name']?></p>
                        <p><?=$item['price']?> €</p>
                    </div>
                </a>

                <?php
            }
            ?>

            <a href="index.php?search=&typesearch=0">voir tout les produits du magasin</a>
        </div>
        <div class="type-event">
            <h2>Des evenements réalisé par des membres actifs</h2>
            <p>prochain évènement : </p>

            <?php
            $req = $bdd->prepare('SELECT id, name, date FROM events WHERE date > NOW() ORDER BY date LIMIT 1');
            $req ->execute();
            $event = $req->fetch();
            if(empty($event)){
                echo '<p> il n\'y a pas d\'évènement prévu pour le moment </p>';
            }else{
                $date = explode(' ', $event['date']);
                $hour = explode(':', $date[1]);
                ?>
         
                <a href="index.php?page=product&id=<?= $event['id']?>" class="exemple">
                    <p><?=$event['name']?></p>
                    <p>le <?= $date[0] ?> </br> à <?= $hour[0].':'.$hour[1]?> </p>
                </a>

                <?php
            }
            ?>
            
            <a href="index.php?page=planning">voir tout les évènements</a>
        </div>
    </div>
</div>
    
<?php
$content = ob_get_clean();
require('view/template.php');