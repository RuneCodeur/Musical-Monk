<header>
    <div id='account'>

        <?php
        if(isset($_SESSION['auth'])){
            echo '<a href="index.php?disconnect">déconnexion <i class="fas fa-sign-out-alt"></i></a>';
            echo '<a href="index.php?page=account">mon compte</a>';

        }else{
            echo '<a href="index.php?page=login">creer un compte / connexion</a>';

        }
        ?>

    </div>

    <div id='title'>
        <img src="public/images/MM.png" alt="logo de Musical Monk" class="logo-mobile">
        <img src="public/images/MM-L.png" alt="logo de Musical Monk" class="logo-desktop">
    </div>

    <div id="navigation">
        <span> 
            <i class="fas fa-chevron-circle-left"></i> 
            <p>naviguer</p>  
            <i class="fas fa-chevron-circle-right"></i> 
        </span>

        <nav>
            <a href="index.php">Accueil</a>
            <a href="index.php?page=planning">évènements</a>
            <a href="index.php?page=contact">contact et infos</a>

            <?php
            if(isset($_SESSION['auth']) AND $_SESSION['auth']['admin'] == 1){
                echo '<a href="index.php?page=addproduct">ajouter un produit</a>';
            }
            ?>
            
        </nav>
    </div>
    
</header>