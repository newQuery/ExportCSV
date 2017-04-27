# ExportCSV
### What is it ?
A class that allows you to export all you database structure and datas into a CSV file

##### Example
```php
<?php
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

// Simple and fast way to create & download
$csv = new CSV($dbh, array('table' => 'chatbox_messages','name' => 'export_chatbox_messages', 'database' => 'chatbox', 'directory' => 'csv'));

// Using chaining to download
$csv = new CSV($dbh, array('table' => 'mybb_usergroups','name' => 'export_mybb_usergroup', 'database' => 'chatbox', 'directory' => 'csv'));
$csv -> fetchResult() -> setContent() -> createCSV() -> download();

// You can also just create the csv and place it into the directory of your choice
$csv -> fetchResult() -> setContent() -> createCSV();
```
