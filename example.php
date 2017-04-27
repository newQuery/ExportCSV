<?php
require 'class.CSV.php';
try
{
    $dbh = new PDO('mysql:host=localhost;dbname=myBB', 'root', '');
}
catch (PDOException $e)
{
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

$csv = new CSV($dbh, array('table' => 'mybb_usergroups','name' => 'export_mybb_templates', 'database' => 'myBB', 'directory' => 'csv'));
