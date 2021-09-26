<?php $title = 'Musical-Monk';
ob_start();
if(!isset($_GET['search']) && !isset($_GET['typesearch'])){
    header('location:index.php');
    die();
}
$typesearch = $_GET['typesearch'];
$search = $_GET['search'];

require_once ('view/backend/connectDB.php');
if($typesearch > 0){
    $req = $bdd->prepare('SELECT product.id as id, product.name as name, product_type.name as type, product.price as price FROM product INNER JOIN product_type ON product.type = product_type.id WHERE MATCH (product.name, product.description) AGAINST(:value) AND product.type = :type');
    $req ->execute(array(
        'value' =>'FRENCH "' . str_replace(' ', '","', htmlspecialchars($search)) .'"',
        'type' => $typesearch
    ));
    $response = $req->fetchAll();
}else{
    $req = $bdd->prepare('SELECT product.id as id, product.name as name, product_type.name as type, product.price as price FROM product INNER JOIN product_type ON product.type = product_type.id WHERE MATCH (product.name, product.description) AGAINST(:value)');
    $req ->execute(array(
    'value' =>'FRENCH "' . str_replace(' ', '","', htmlspecialchars($search)) .'"'
    ));
    $response = $req->fetchAll();
}

?>
<div class="page research">

    <form action="index.php?" method="get" class="research-item">
        <fieldset>
            <div class="param-search">
            <input type="text" name="search" placeholder="Je recherche..." class="search-words">
                <div>
                    <input type="radio" name="typesearch" value="0" id="type0" class="search-type" checked>
                    <label for="type0">toutes catégories</label>
                </div>

                <?php
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

    <div class="result">
        <h2>résultat de la recherche</h2>
        <div class="array">
            <p class="produit">produit</p>
            <p class="categorie">catégorie</p>
            <p class="prix">prix</p>
        </div>
        <ul>

            <?php
            if(COUNT($response) > 0){   
                foreach($response as $product){
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
                echo '<div class="warn"> aucun résultat ne correspond à votre recherche. </div>';
            }
            ?>

        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include('view/template.php');