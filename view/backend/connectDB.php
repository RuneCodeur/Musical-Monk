<?php 

$bdd = new PDO('mysql:host=localhost;dbname=musical_monk;charset=utf8;port=3307', 'root', 'root');
$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);