# ExportCSV
### What is it ?
A class that allows you to export all you database structure and datas into a CSV file

##### Example
```php
<?php
require 'class.CSV.php';
try
{
    $dbh = new PDO('mysql:host=localhost;dbname=hexui', 'root', '');
}
catch (PDOException $e)
{
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

// You statement, select from the database you want to export
$PDOStatement = $dbh -> prepare("SELECT * FROM mybb_usergroups");

// You can also bind values
$PDOStatement = $dbh -> prepare("SELECT * FROM mybb_usergroups WHERE type = :type");
$PDOStatement -> bindValue(':type', '1');

// Easiest & fastest way
$csv = new CSV($PDOStatement);
$csv -> download();

// Adding a directory + name
$csv = new CSV($PDOStatement);
$csv -> set('name', 'export_myBB_usergroup') -> set('directory', 'csv');
$csv -> download();

// Creating the CSV but not download it
$csv = new CSV($PDOStatement);
$csv -> set('name', 'export_myBB_usergroup') -> set('directory', 'csv');
$csv -> create();
```
