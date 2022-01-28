<div class="page research">

    <form action="index.php?" method="get" class="research-item">
        <fieldset>
            <div class="param-search">
            <input type="text" name="search" placeholder="Je recherche..." class="search-words">
                <div>
                    <input type="radio" name="typesearch" value="0" id="type0" class="search-type" <?php if( $_GET['typesearch'] == 0){ echo 'checked';} ?>>
                    <label for="type0">Toutes catégories</label>
                </div>

                <?php
                    foreach($listTypes as $type){
                        {
                        ?>
                            <div>
                                <input type="radio" name="typesearch" value="<?=$type['id']?>" id="type<?=$type['id']?>"  class="search-type" <?php if( $_GET['typesearch'] == $type['id']){ echo 'checked';} ?>>
                                <label for="type<?=$type['id']?>"><?=$type['name']?></label>
                            </div>
                        <?php
                        }
                    }
                ?>

            </div>
            <input type="submit" value="Rechercher" class="search-submit input-style">
        </fieldset>
    </form>

    <div class="result">
        <h2>Résultat de la recherche</h2>
        <div class="array">
            <p class="produit">Produit</p>
            <p class="categorie">Catégorie</p>
            <p class="prix">Prix</p>
        </div>
        <ul>

            <?php
            if($listProduct != null){
                foreach($listProduct as $product){
                    {?>
                        <li>
                            <a href="index.php?page=product&id=<?= $product['id']?>">
                                <p class="produit"><?= $product['name']?></p>
                                <p class="categorie"><?= $product['type']?></p>
                                <p class="prix"><?= $product['price']?>€</p>
                            </a>
                        </li>
                    <?php
                    }
                }
            }else{
                echo '<div class="warn"> Aucun résultat ne correspond à votre recherche. </div>';
            }
            ?>

        </ul>
    </div>
</div>