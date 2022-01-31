<?php

interface SendMailInterface {
    
    function MailToNewUser(int $id, string $token, string $mailUser);
}

interface ReservationEventInterface {

    function Reservation (int $user, int $event, int $friend): bool;

    function ListAllUsersReservedEvent (int $id): ?array;

    function ListMyEventsReserved (int $user): ?array;

    function DeleteMyEventReservation (int $reservation, int $user): bool;
}

interface ReservationProductInterface {

    function Reservation(int $user, int $product, int $quantity): bool;

    function ListMyProductsReserved(int $user): ?array;

    function DeleteMyProductReservation(int $product, int $id): bool;
}

interface EventInterface {

    function AllEvent (): ?array;

    function ActualPage(): array;

    function RandomEvent() : ?array;

    function OneEvent (int $id): ?array;

    function CreateEvent(int $user, array $newEvent): bool;

    function ModifyEvent(int $event, array $modifyEvent): bool;
    
    function ListMyEvents (int $user): ?array;
}

interface ProductInterface {

    function SearchProduct (int $type, string $search): ?array;

    function AllTypeProduct() : ?array;

    function RandomProduct() : ?array;
    
    function OneProduct(int $id): ?array;
}

interface AdminInterface {

    function CreateProduct (array $product, array $picture): bool;
}

interface UserInterface {
    public function CreateUser(string $pseudo, string $mail, string $password, string $confirmPassword);

    public function ConnectUser(string $pseudo, string $password): array;

    public function MailConfirm(int $id, string $token): bool;

    public function ModifyUser(int $user, array $item): bool;
      
    public function TestAdmin(int $user): bool;
}