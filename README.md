(This was experimental, don't use this in production)

# ExportCSV
### What is it ?
A class that allows you to export all you database structure and datas into a CSV file

### Example

##### First include the class & set-up you database connection
```php
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
```
###### Next write your PDO statement
```php
// You statement, select from the database you want to export
$PDOStatement = $dbh -> prepare("SELECT * FROM mybb_usergroups");

// You can also bind values
$PDOStatement = $dbh -> prepare("SELECT * FROM mybb_usergroups WHERE type = :type");
$PDOStatement -> bindValue(':type', '1');
```
###### Now you have the choice
```php
// Easiest & fastest way
$csv = new CSV($PDOStatement);
$csv -> download();

// Adding a directory + name
$csv = new CSV($PDOStatement);
$csv -> set('name', 'export_myBB_usergroup') -> set('directory', 'csv');
$csv -> download();
```
###### Or simply create it without download
```php
// Creating the CSV but not download it
$csv = new CSV($PDOStatement);
$csv -> set('name', 'export_myBB_usergroup') -> set('directory', 'csv');
$csv -> create();
```
