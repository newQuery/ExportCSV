<?php
// re Test
error_reporting(E_ALL);
require 'class.CSV.php';
try
{
    $dbh = new PDO('mysql:host=localhost;dbname=random', 'root', 'root');
}
catch (PDOException $e)
{
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

$PDOStatement = $dbh -> prepare("SELECT * FROM messages");


$csv = new CSV($PDOStatement);
#$csv -> download();
