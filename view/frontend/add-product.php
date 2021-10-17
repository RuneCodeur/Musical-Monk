<?php $title = 'Musical-Monk';
ob_start();
if(!isset($_SESSION['auth'])){
    header('location: index.php?err=disconnect');
    die();
}

elseif(isset($_SESSION['auth']['id']) AND isset($_SESSION['auth']['pseudo'])){
    require_once ('view/backend/connectDB.php');
    $req = $bdd->prepare('SELECT admin FROM users WHERE pseudo = :pseudo AND id = :id');
    $req ->execute(array(
        'pseudo' => $_SESSION['auth']['pseudo'],
        'id' => $_SESSION['auth']['id']
    ));
    $user = $req->fetch();
    if($user['admin'] != 1){
        session_unset();
        session_destroy();
        header('location: index.php?err=unauthorized');
        die();
    }
}else{
    header('location: index.php?err=disconnect');
    die();
}
if(!empty($_POST || $_FILES)){
    include('view/backend/create-product.php');
}

if (!empty($errorsCreate)){
    ?>

    <div class="err">
        <p>vous n'avez pas rempli le formulaire correctement :</p>
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

<div class="page addproduct">

<h1> ajouter un produit</h1>
    <div class="warn"> ATTENTION : Cette page est réservé aux admins. </div>

    <form method="post" action="index.php?page=addproduct" enctype="multipart/form-data">
        <fieldset>
            <div>
                <label for="title">nom du produit : </label>
                <input type="text" name="title">
            </div>
            <div>
                <label for="picture">photo : </label>
                <input type="file" name="picture" id="picture">
            </div>
            
            <div class="check">
                <?php
                require_once ('view/backend/connectDB.php');
                $req = $bdd->prepare('SELECT * FROM product_type');
                $req ->execute();
                while($listType = $req->fetch()){
                    {?>
                        <div>
                            <input type="radio" name="type" value="<?=$listType['id']?>" id="type<?=$listType['id']?>"<?php if($listType['id'] == 1){ echo 'checked';}?>>
                            <label for="type<?=$listType['id']?>"><?=$listType['name']?></label>
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
            <div>
                <label for="quantity">quantité disponible : </label>
                <input type="number" name="quantity" class="number" value='1'>
            </div>
            <div>
                <label for="price">prix (euros) : </label>
                <div class="price"><input type="number" name="price" class="number" step="0.01">€</div>
            </div>
            <div>
                <label for="description"> description : </label>
                <textarea name="description"></textarea>
            </div>
        </fieldset>
            <input type="submit" value="enregistrer le produit">
    </form>
</div>

<?php
$content = ob_get_clean();
require('view/template.php');