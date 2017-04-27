<?php
// test Git
require 'class.CSV.php';
try
{
    $dbh = new PDO('mysql:host=localhost;dbname=chatbox', 'root', '');
}
catch (PDOException $e)
{
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

$csv = new CSV($dbh, array('table' => 'mybb_usergroups','name' => 'export_mybb_usegroups', 'database' => 'chatbox', 'directory' => 'csv', 'download' => 1));
