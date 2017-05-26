<?php
require 'class.CSV.php';
try
{
    $dbh = new PDO('mysql:host=localhost;dbname=c9', 'root', '');
}
catch (PDOException $e)
{
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

// You statement, select from the database you want to export

// You can also bind values
$PDOStatement = $dbh -> prepare("SELECT * FROM mybb_lobbies WHERE open = :type");
$PDOStatement -> bindValue(':type', '1');

// Easiest & fastest way
$csv = new CSV($PDOStatement);
$csv -> download();
