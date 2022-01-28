<div class="page product">

    <h1><?= $product['name']?></h1>

    <div>
        <img src="<?=$product['picture']?>" alt="<?=$product['name']?>">
        <div class="carac-product">
            <p><?=$product['description']?></p>
            
            <p class="price">Prix (unité) : <?=$product['price']?> €</p>
            <p class="quantity">Disponible : <?=$product['quantity']?></p>
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