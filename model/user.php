<?php
include_once('model/connectDB.php');
include_once('model/calcul.php');
include_once('model/mail.php');


final class User extends ConnectDB {

    public function CreateUser(string $pseudo, string $mail, string $password, string $confirmPassword) {
        $testPseudo = $this->TestPseudo($pseudo);
        $testMail = $this->TestMail($mail);
        $testPassword = $this->TestPassword($password, $confirmPassword);
        
        $bdd = parent::Connection();
        $token = Calcul::StrRandom();
        $req = $bdd->prepare('INSERT INTO users(pseudo, mail, mdp, token_validation) VALUES(:pseudo, :mail, :mdp, :token)');
        $req->execute(array('pseudo' => $pseudo ,'mail' => $mail, 'mdp' => password_hash($password, PASSWORD_BCRYPT), 'token' => $token));
            
        $id = $bdd->lastInsertId();
        $sendMail = new SendMail;
        $sendMail -> MailToNewUser( $id, $token, $mail);

        return true;
    }

    public function ConnectUser(string $pseudo, string $password): array {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT * FROM users WHERE pseudo = :pseudo');
        $req ->execute(array('pseudo' => $pseudo));
        $user = $req->fetch();
        if(!$user){
            throw new Exception("Cet utilisateur n'existe pas.");
        }
        else{
            if(!password_verify($password, $user['mdp'])){
                throw new Exception("Le mot de passe est incorrect.");
            }
            if($user['date_validation'] == NULL){
                throw new Exception("Vous n'avez pas validé votre adresse mail.");
            }
            else{
                return $user;
            }
        }
    }

    public function MailConfirm(int $id, string $token): bool {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT id FROM users WHERE id =:id AND token_validation =:token');
        $req->execute(array('id' => $id, 'token' =>$token ));
        $response = $req->fetch();
        if(!$response){
            return false;
        }
        else{
            $req = $bdd->prepare('UPDATE users SET token_validation = NULL, date_validation = NOW() WHERE id =:id');
            $req->execute(array('id' => $id));
            return true;
        }
    }
    
    private function TestPseudo(string $pseudo): bool {
        if(!preg_match('/^[a-zA-Z0-9_]+$/', $pseudo)){
            throw new Exception("votre pseudo n'est pas conforme.");
        }
        else{
            $bdd = parent::Connection();
            $req = $bdd -> prepare('SELECT id FROM users WHERE pseudo = :pseudo');
            $req -> execute(array( 'pseudo' => $pseudo ));
            $response = $req->fetch();
            if($response){
                throw new Exception('pseudo déja utilisé.');
            }
            else{
                return true;
            }
        }
    }

    private function TestMail(string $mail): bool {
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            throw new Exception("votre email n'est pas conforme.");
        }else{
            $bdd = parent::Connection();
            $req = $bdd->prepare('SELECT id FROM users WHERE mail = :mail');
            $req ->execute(array( 'mail' => $mail ));
            $response = $req->fetch();
            if($response){
                throw new Exception("adresse mail déja utilisé.");
                
            }
            else{
                return true;
            }
        }
    }
    
    private function TestPassword(string $password,string $confirmPassword): bool {
        if($password != $confirmPassword){
            throw new Exception("le mot de passe n'est pas valide.");
            
        }
        else{
            return true;
        }
    }

    public function ModifyUser(int $user, array $item): bool{
        $value = reset($item);
        if($value == ''){
            throw new Exception("Vous ne pouvez pas modifier votre compte avec rien du tout.");
        }else{
            switch(array_key_first($item)){
                case 'modify-pseudo':
                    $response = $this->ModifyPseudo($user, $item);
                    if($response){
                        $_SESSION['auth']['pseudo'] = $item['modify-pseudo'];
                    }
                break;

                case 'modify-mail':
                    $response = $this->ModifyMail($user, $item);
                    if($response){
                        $_SESSION['auth']['mail'] = $item['modify-mail'];
                    }
                break;

                case 'modify-mdp':
                    $response = $this->ModifyPassword($user, $item);
                    if($response){
                        $_SESSION['auth']['mdp'] = $item['modify-mdp'];
                    }
                break;
            }
            return $response;
        }
    }

    private function ModifyPseudo(int $user, array $item): bool {
        $testPseudo = $this->TestPseudo($item['modify-pseudo']);
        $bdd = parent::Connection();
        $req = $bdd->prepare('UPDATE users SET pseudo = :pseudo WHERE id = :id');
        $req->execute(array('pseudo' => $item['modify-pseudo'],'id' => $user ));
        return true;
    }

    private function ModifyMail(int $user, array $item): bool {
        $testMail = $this->TestMail($item['modify-mail']);
        $bdd = parent::Connection();
        $req = $bdd->prepare('UPDATE users SET mail = :mail WHERE id = :id');
        $req->execute(array( 'mail' => $item['modify-mail'], 'id' => $id ));
        return true;
    }

    private function ModifyPassword(int $user, array $item): bool {
        $testPassword = $this->TestPassword($item['confirm-modify-mdp'],$item['confirm-modify-mdp']);
        $bdd = parent::Connection();
        $req = $bdd->prepare('UPDATE users SET mdp = :mdp WHERE id = :id');
        $req->execute(array( 'mdp' => password_hash($item['modify-mdp'], PASSWORD_BCRYPT), 'id' => $user ));
        return true;
    }

    public function TestAdmin(int $user): bool {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT admin FROM users WHERE id = :id');
        $req->execute(array('id' => $user));
        $admin = $req->fetch();
        if(!$admin){
            print_r($admin);
            return false;
        }
        else{
            if($admin['admin'] == 1 ){
                return true;
            }
            else{
                return false;
            }
        }
    }

}