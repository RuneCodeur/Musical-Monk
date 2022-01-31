<?php
include_once('model/connectDB.php');
include_once('model/test.php');
include_once('model/interfaces.php');

final class Event extends ConnectDB implements EventInterface {
    use TestAware;

    public function AllEvent (): ?array {
        if (isset($_GET['eventpage'])){
            if($_GET['eventpage']<1){
                $page = 1;
            }
            else{
                $page = $_GET['eventpage'];
            }
        }
        else{
            $page = 1;
        }
        $bdd = parent::connection();
        $req = $bdd -> prepare('SELECT events.id as id, events.name as name, users.pseudo as creator, events.registration as registration, events.max_registration as max_registration, events.date as date FROM events INNER JOIN users ON events.creator = users.id WHERE date > NOW() ORDER BY date LIMIT 10 OFFSET :eventpage');
        $req->bindValue('eventpage', ( $page * 10) - 10, PDO::PARAM_INT);
        $req -> execute();
        $response = $req->fetchall();
        if($response == false){
            return null;
        }
        else{
            return $response;
        }
    }

    public function ActualPage(): array {
        if (isset($_GET['eventpage'])){
            if($_GET['eventpage']<1){
                $page = 1;
            }
            else{
                $page = $_GET['eventpage'];
            }
        }
        else{
            $page = 1;
        }
        $bdd = parent::connection();
        $req = $bdd -> prepare('SELECT id FROM events WHERE date > NOW() ORDER BY date LIMIT 1 OFFSET :eventpage');
        $req->bindValue('eventpage', ( $page * 10) + 1, PDO::PARAM_INT);
        $req -> execute();
        if ($response = $req->fetch()){
            return array(
                'page'=> $page, 
                'next'=> true
            );
        }
        else{
            return array(
                'page'=> $page, 
                'next'=> false
            );
        }
    }

    public function RandomEvent() : ?array {
        $bdd = parent::Connection();
        $req = $bdd -> prepare('SELECT id, name, date FROM events WHERE date > NOW() ORDER BY date LIMIT 1');
        $req -> execute();
        $response = $req->fetch();
        if($response == false){
            return null;
        }else{
            return $response;
        }
    }

    public function OneEvent (int $id): ?array {
        $bdd = parent::Connection();
        $req = $bdd -> prepare('SELECT events.id as id, events.creator as idcreator, events.name as name, events.description as description, events.date as date, events.duration as duration, events.registration as registration, events.max_registration as max_registration, users.pseudo as creator FROM events INNER JOIN users ON events.creator = users.id WHERE events.id = :id');
        $req ->execute(array( 'id' => $id ));
        $req -> execute();
        $response = $req->fetch();
        if($response == false){
            return null;
        }
        else{
            return $response;
        }
    }

    public function CreateEvent(int $user, array $newEvent): bool {
        $testTitle = $this->TestTitle($newEvent['name']);
        $testDescription = $this->TestDescription($newEvent['description']);
        $testRegistration = $this->TestPostiveNumber($newEvent['maxRegistration']);

        if($testTitle != true){
            throw new Exception("Le titre possède un ou plusieurs caractères interdits ( | * # @ [] <> {} € \$ ¤ £ § ).");
        }
        if($testDescription != true){
            throw new Exception("La description possède un ou plusieurs caractères interdits ( | [] <> {} ¤ § ).");
        }
        if($testRegistration != true){
            throw new Exception("Vous devez renseigner une quantité valide.");
        }

        $bdd = parent::Connection();
        $req = $bdd->prepare('INSERT INTO events(name, description, date, duration, creator, max_registration) VALUES(:name, :description, :date, :duration, :creator, :max_registration)');
        $req->execute(array(
            'name' => strip_tags($newEvent['name']),
            'description' => strip_tags($newEvent['description']),
            'date' => strip_tags($newEvent['date']) . ' ' . strip_tags($newEvent['time']),
            'duration' => strip_tags($newEvent['duration']),
            'creator' => $user,
            'max_registration' => strip_tags($newEvent['maxRegistration'])
        ));
        return true;
    }

    public function ModifyEvent(int $event, array $modifyEvent): bool {
        $testTitle = $this->TestTitle($modifyEvent['name']);
        $testDescription = $this->TestDescription($modifyEvent['description']);

        if($testTitle != true){
            throw new Exception("Le titre possède un ou plusieurs caractères interdits ( | * # @ [] <> {} € \$ ¤ £ § ).");
        }
        if($testDescription != true){
            throw new Exception("La description possède un ou plusieurs caractères interdits ( | [] <> {} ¤ § ).");
        }
        $bdd = parent::Connection();
        $req = $bdd->prepare('UPDATE events SET name =:name, description =:description, date =:date, duration =:duration WHERE id = :id');
        $req->execute(array(
            'name' => strip_tags($modifyEvent['name']),
            'description' => strip_tags($modifyEvent['description']),
            'date' => strip_tags($modifyEvent['date']) . ' ' . strip_tags($modifyEvent['time']),
            'duration' => strip_tags($modifyEvent['duration']),
            'id' => $event
        ));
        return true;
    }
    
    public function ListMyEvents (int $user): ?array {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT name, id, date, max_registration, registration FROM events WHERE creator = :user AND date > NOW() ORDER BY date');
        $req ->execute(array('user' => $user ));
        $response = $req->fetchall();
        if($response === false){
            return null;
        }
        else{
            return $response;
        }
    }
}