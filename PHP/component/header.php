<header>
    <div id='account'>
        <?php
        if(isset($_COOKIE['pseudo']))
        {
            $connection = $_COOKIE['pseudo'];
            {
        ?>

            <a href="index.php?page=account">mon compte</a>

        <?php
            }
        }
        else
        {
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
        <a href="index.php?page=addproduct">ajouter un produit</a>
    </nav>
</header>