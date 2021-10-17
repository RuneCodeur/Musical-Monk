<?php 
//connecte la base de donnée
$bdd = new PDO('mysql:host=localhost;dbname=musical_monk;charset=utf8;port=3308', 'root', 'root');
$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);