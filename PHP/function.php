<?php
//déclare toutes les fonctions

function str_random($length){
    $caractere = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!?$@";
    return substr(str_shuffle(str_repeat($caractere, $length)), 0, $length);
}