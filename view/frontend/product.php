<?php $title = 'Musical-Monk';
ob_start();

if(!isset($_GET['id'])){
    header('location:index.php');
    die();
}

if(isset($_POST['quantity'])){
    require_once ('view/backend/reserve-product.php');
}
if (!empty($errorsCreate)){
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

require_once ('view/backend/connectDB.php');
$req = $bdd->prepare('SELECT product.name as name, product_type.name as type, product.description as description, product.picture as picture, product.quantity as quantity, product.price as price FROM product INNER JOIN product_type ON product.type = product_type.id WHERE product.id = :id');
$req ->execute(array(
    'id' => $_GET['id']
));
$product = $req->fetch();
?>

<div class="page product">
    <h1><?= $product['name']?></h1>
    <div>
        <img src="<?=$product['picture']?>" alt="<?=$product['name']?>">
        <div class="carac-product">
            <p><?=$product['description']?></p>

            <?php 
            
            if($product['quantity'] <= 0){
                echo '<div class"warn"> ce produit n\'est plus disponible à la reservation. </div>';
            }
            elseif(!isset($_SESSION['auth'])){
                echo '<div class"warn"> vous devez vous connecter pour pouvoir reserver un produit. </div>';
            }
            else{
            ?>

            <form method="post" action="index.php?page=product&id=<?=$_GET['id']?>">
                <fieldset>
                    <p class="price">Prix (unité) : <?=$product['price']?> €</p>
                    <p class="quantity">Disponible : <?=$product['quantity']?></p>
                    <div>
                        <label for="quantity"> Quantité que je reserve : </label>
                        <input type="number" name="quantity" id="quantity" value="1">
                    </div>
                    <input type="submit" id="submit" value="je reserve ce produit" class="button-style">
                </fieldset>
            </form>
            
            <?php 
            }
            ?>

        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require('view/template.php');