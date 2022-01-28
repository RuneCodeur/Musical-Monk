<div class="page addproduct">

<h1>Ajouter un produit</h1>

    <div class="warn"> ATTENTION : Cette page est réservé aux admins. </div>

    <form method="post" action="index.php?page=addproduct" enctype="multipart/form-data">
        <fieldset>
            <div>
                <label for="title">Nom du produit : </label>
                <input type="text" name="title">
            </div>
            <div>
                <label for="picture">Photo : </label>
                <input type="file" name="picture" id="picture">
            </div>
            
            <div class="check">

                <?php
                foreach($listTypes as $listType){
                    {?>
                        <div>
                            <input type="radio" name="type" value="<?=$listType['id']?>" id="type<?=$listType['id']?>"  class="type">
                            <label for="type<?=$listType['id']?>"><?=$listType['name']?></label>
                        </div>
                    <?php
                    }
                }
                ?>
                
            </div>
            <div>
                <label for="quantity">Quantité disponible : </label>
                <input type="number" name="quantity" class="number" value='1'>
            </div>
            <div>
                <label for="price">Prix (euros) : </label>
                <div class="price"><input type="number" name="price" class="number" step="0.01">€</div>
            </div>
            <div>
                <label for="description">Description : </label>
                <textarea name="description"></textarea>
            </div>
        </fieldset>
            <input type="submit" value="enregistrer le produit" class="input-style">
    </form>
</div>