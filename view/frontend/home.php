<?php $title = 'Musical-Monk';
ob_start();
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
    <form action="index.php?" method="get" class="research-item">
        <fieldset>
            <div class="param-search">
            <input type="text" name="search" placeholder="Je recherche..." class="search-words">
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
    <p>presentation rapide du site</p>
    <p>prochain evenement</p>
</div>
    
<?php
$content = ob_get_clean();
require('view/template.php');