<?php
include_once('model/connectDB.php');
include_once('model/interfaces.php');

class ReservationEvent extends ConnectDB implements ReservationEventInterface {

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

    public function ListAllUsersReservedEvent (int $id): ?array {
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

    public function ListMyEventsReserved (int $user): ?array {
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

    public function DeleteMyEventReservation (int $reservation, int $user): bool {
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