<?php $title = 'Musical-Monk';
ob_start();

if(isset($_GET['err'])){
    if($_GET['err'] == 'invalidtoken'){
        echo '<div class="err"> désolé, ce lien n\'est pas valide.</div>';
    }
}

if(!empty($_POST)){
    if (isset($_POST['create-pseudo']) || isset($_POST['create-mail']) || isset($_POST['create-mdp']) || isset($_POST['confirm-mdp'])){
        include('view/backend/createAccount.php');
    }
    if (isset($_POST['connect-pseudo']) || isset($_POST['connect-mdp'])){
        include('view/backend/connectAccount.php');
    }
}
?>

<div class="page login">
    <h1>connexion</h1>

    <?php if (!empty($errorsCreate)){
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
    
    if(!empty($_GET['error'])){
        echo '<div class="err"> ce lien n\'est pas valide. </div>';
    }

    if (!empty($errorsConnect))
        echo '<div class="err">' . $error . '</div>';
    ?>

    <div class="ensemble-connection">
        <div class="connection-account">
            <h2>j'ai déja un compte</h2>
            <form method="post" action="index.php?page=login">
                <fieldset>
                    <div>
                        <label for="pseudo">pseudo :</label>
                        <input type="text" name="connect-pseudo" required>
                    </div>

                    <div>
                        <label for="mdp">mot de passe :</label>
                        <input type="password" name="connect-mdp" required>
                    </div>

                    <input class="submit" type="submit" value="connexion" >
                </fieldset>
            </form>
        </div>

        <div class="create-account">
            <h2> je crée un compte</h2>
            <form method="post" action="index.php?page=login">
                <fieldset>
                    <div>
                        <label for="pseudo">pseudo :</label>
                        <input type="text" id="pseudo" name="create-pseudo" >
                    </div>
                    
                    <div>
                        <label for="mail">mail :</label>
                        <input type="email" id="mail" name="create-mail" >
                    </div>
                    
                    <div>
                        <label for="mdp"> mot de passe :</label>
                        <input type="password" id="mdp" name="create-mdp" onkeyup="check()">
                    </div>

                    <div>
                        <label for="confirm-mdp"> confirmation du mot de passe :</label>
                        <input type="password" id="confirm-mdp" name="confirm-mdp" onkeyup="check()" >
                    </div>
                    
                    <input id="input-create-account" class="submit" type="submit" value=" je crée mon compte">
                </fieldset>
            </form>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
include('view/template.php');