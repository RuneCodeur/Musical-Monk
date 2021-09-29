<header>
    <div id='account'>
        <?php
        $admin = 0;
        if(isset($_SESSION['auth'])){
            if($_SESSION['auth']['admin'] == 1){
                $admin = 1;
            }
            $connection = $_SESSION['auth']['pseudo'];
            {
            ?>
            <a href="index.php?page=account">mon compte</a>
            <?php
            }
        }else{
            {
            ?>
            <a href="index.php?page=login">creer un compte / connexion</a>
            <?php

            }
        }
        ?>

    </div>
    <div id='title'>
        <img src="public/images/MM.png" alt="logo de Musical Monk">
    </div>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="index.php?page=planning">évènements</a>
        <a href="index.php?page=contact">contact et infos</a>
        <?php
        if ($admin == 1){
            echo '<a href="index.php?page=addproduct">ajouter un produit</a>';
        }
        ?>
    </nav>
    
</header>