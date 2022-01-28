<?php
class calcul {

    public static function ExplodeDate (string $calandar) : array {
        $date = explode(' ', $calandar);
        $day = explode('-', $date[0]);
        $hour = explode(':', $date[1]);
        return array(
            'day' => $day,
            'hour' => $hour
        );
    }

    public static function ExplodeDuration (string $duration) : array {
        return explode(':',$duration);
    }

    public static function StrRandom(): string {
        $caractere = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!?$@";
        return substr(str_shuffle(str_repeat($caractere, 60)), 0, 60);
    }

}