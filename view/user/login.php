<div class="page login">
    
    <h1>Connexion</h1>

    <div class="ensemble-connection">
        <div class="connection-account">
            <h2>J'ai déja un compte</h2>
            <form method="post" action="index.php?page=login">
                <fieldset>
                    <div>
                        <label for="pseudo">Pseudo :</label>
                        <input type="text" name="connect-pseudo" required>
                    </div>

                    <div>
                        <label for="mdp">Mot de passe :</label>
                        <input type="password" name="connect-mdp" required>
                    </div>

                    <input class="submit input-style" type="submit" value="connexion" >
                </fieldset>
            </form>
        </div>

        <div class="create-account">
            <h2>Je crée un compte</h2>
            <form method="post" action="index.php?page=login">
                <fieldset>
                    <div>
                        <label for="pseudo">Pseudo :</label>
                        <input type="text" id="pseudo" name="create-pseudo" >
                    </div>
                    
                    <div>
                        <label for="mail">Mail :</label>
                        <input type="email" id="mail" name="create-mail" >
                    </div>
                    
                    <div>
                        <label for="mdp">Mot de passe :</label>
                        <input type="password" id="mdp" name="create-mdp" onkeyup="check()">
                    </div>

                    <div>
                        <label for="confirm-mdp">Confirmation du mot de passe :</label>
                        <input type="password" id="confirm-mdp" name="confirm-mdp" onkeyup="check()" >
                    </div>
                    
                    <input id="input-create-account" class="submit input-style" type="submit" value=" je crée mon compte">
                </fieldset>
            </form>
        </div>
    </div>

</div>