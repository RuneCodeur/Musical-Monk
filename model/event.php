<?php
include_once('model/connectDB.php');

final class Event extends ConnectDB {

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

    public function AllReserved (int $id): ?array {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT users.pseudo as user, reserved.user as userid, reserved.friend as friend FROM reserved INNER JOIN users ON reserved.user = users.id WHERE reserved.event = :id ');
        $req ->execute(array( 'id' => $id ));
        $req -> execute();
        $response = $req->fetchall();
        if($response == false){
            return null;
        }
        else{
            return $response;
        }
    }

    public function Reservation (int $user, int $event, int $friend): bool {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT registration FROM events WHERE id= :id ');
        $req ->execute(array('id' => $event));
        $registration = $req->fetch();
        if($registration === false){
            throw new Exception("Désolé, cet évènement n'existe plus.");
        }
        else{
            $req = $bdd->prepare('SELECT id FROM reserved WHERE user = :user AND event = :event');
            $req ->execute(array('user' => $user,'event' => $event ));
            $registred = $req->fetch();
            if($registred){
                throw new Exception("Vous êtes déja enregistré pour cet évènement.");
            }
            else{
                $req = $bdd->prepare('INSERT INTO reserved (user, event, friend) VALUE (:user, :event, :friend)');
                $req ->execute(array('user' => $user,'event' => $event ,'friend' => $friend ));

                $newRegistration = $registration['registration'] + 1 + $friend;
                $req = $bdd->prepare('UPDATE events SET registration = :registration WHERE id = :id');
                $req ->execute(array('registration' => $newRegistration,'id' => $event));
                return true;
            }
        }
    }

    public function CreateEvent(int $user, array $newEvent): bool {
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
    
    public function EventReserved (int $user): ?array {
        $bdd = parent::Connection();
        $req = $bdd->prepare('SELECT events.id as event, reserved.id as id, reserved.friend as friend, events.name as name, events.date as date FROM reserved INNER JOIN events ON reserved.event = events.id WHERE reserved.user = :user AND date > NOW() ORDER BY date ');
        $req ->execute(array('user' => $user ));
        $response = $req->fetchall();
        if($response === false){
            return null;
        }
        else{
            return $response;
        }
    }
    
    public function MyEvents (int $user): ?array {
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

    public function DeleteReservation(int $reservation, int $user): bool {
        $bdd = parent::Connection();

        $req = $bdd->prepare('SELECT * FROM reserved WHERE id= :id ');
        $req ->execute(array( 'id' => $reservation ));
        $reserved = $req->fetch();
        $removeUser = 1 + $reserved['friend'];
        if($reserved === false){
            throw new Exception("Vous n'avez pas fait de reservations.");
        }
        elseif($reserved['user'] != $user){
            throw new Exception("Vous n'avez pas reservé pour cet évènement.");            
        }
        else{
            $req = $bdd->prepare('DELETE FROM reserved WHERE id = :id');
            $req ->execute(array('id' => $reservation));

            $req = $bdd->prepare('UPDATE events SET registration = registration - :removeuser WHERE id = :id');
            $req ->execute(array( 'removeuser' => $removeUser, 'id' => $reserved['event']));
            return true;
        }
    }
}