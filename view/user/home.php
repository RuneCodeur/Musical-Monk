<div class="page home">

    <h1>Tout ce qu'il faut pour mettre la musique en oeuvre</h1>

    <form action="index.php" method="get" class="research-item">
        <fieldset>
            <div class="param-search">
            <input type="text" name="search" placeholder="rechercher un produit..." class="search-words">
                <div>
                    <input type="radio" name="typesearch" value="0" id="type0" class="search-type" checked>
                    <label for="type0">toutes catégories</label>
                </div>

                <?php
                    foreach($listTypes as $listType){
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
            <input type="submit" value="Rechercher" class="search-submit input-style">
        </fieldset>
    </form>
    <div class="presentation">
        <div class="type-product">
            <h2>Des instruments et des accessoires de tout genre</h2>
            <p>Un produit aléatoire...</p>

            <?php
            if(empty($randomProduct)){
                echo '<p> il n\'y a plus de produit à vendre dans la boutique </p>';
            }else{
                ?>
        
                <a href="index.php?page=product&id=<?= $randomProduct['id']?>" class="exemple">
                    <img src="<?=$randomProduct['picture']?>" alt="<?=$randomProduct['name']?>">
                    <div>
                        <p><?=$randomProduct['name']?></p>
                        <p><?=$randomProduct['price']?> €</p>
                    </div>
                </a>

                <?php
            }
            ?>

            <a href="index.php?search=&typesearch=0">Voir tout les produits du magasin</a>
        </div>
        <div class="type-event">
            <h2>Des évènements réalisé par des membres actifs</h2>
            <p>Prochain évènement : </p>

            <?php
            if(empty($randomEvent)){
                echo '<p> il n\'y a pas d\'évènement prévu pour le moment </p>';
            }else{
                $date = explode(' ', $randomEvent['date']);
                $day = explode('-', $date[0]);
                $hour = explode(':', $date[1]);
                ?>
        
                <a href="index.php?page=event&id=<?= $randomEvent['id']?>" class="exemple">
                    <p><?=$randomEvent['name']?></p>
                    <p>Le <?= $day[2]?>/<?= $day[1]?>/<?= $day[0]?> </br> à <?= $hour[0].':'.$hour[1]?> </p>
                </a>

                <?php
            }
            ?>

            <a href="index.php?page=planning">Voir tout les évènements</a>
        </div>
    </div>
</div>