<?php
/**
 * @author TheZdo < https://github.com/newQuery >
 * @link https://github.com/newQuery
 */
class CSV
{
    /**
     * @param $db PDO Database connection
     * @param $options Array containing key => value for ['table', 'database', 'name', 'directory']
     */
    public function __construct($db, $options)
    {
        if(!isset($options['table'])) throw new Exception("'table' missing in the options array", 1);
        if(!isset($options['database'])) throw new Exception("'database' missing in the options array", 1);
        if(!isset($options['name'])) throw new Exception("'name' missing in the options array", 1);
        if(!isset($options['directory'])) $options['directory'] = '';
        $this -> options = $options;
        $this -> db = $db;
        if(isset($this -> options['download']) AND $this -> options['download'] == 1) $this -> instantDownload();
    }

    public function instantDownload()
    {
        $this -> fetchResult();
        $this -> setContent();
        $this -> createCSV();
        $this -> download();
    }

    public function fetchResult()
    {
        $stmt = $this -> db -> prepare("SELECT * FROM ". $this -> options['table']);
        $stmt -> execute();
        $count = $stmt -> rowCount();
        if($stmt -> rowCount() < 1)
        {
            throw new Exception("Nothing to export in this table", 1);
            die();
        }
        $results = $stmt -> fetchAll(PDO::FETCH_OBJ);
        $this -> results = $results;
        return $this;
    }

    public function setContent()
    {
        $sql = "SELECT Column_name FROM Information_schema.columns where Table_schema = :database AND Table_name like :table";
        $columns = $this -> db -> prepare($sql);
        $columns -> bindValue(':database', $this -> options['database']);
        $columns -> bindValue(':table', $this -> options['table']);
        $columns -> execute();
        if($columns -> rowCount() < 1)
        {
            throw new Exception("Failled to set content, make sure you put the right options", 1);
            die();
        }
        $columnNames = $columns -> fetchAll(PDO::FETCH_OBJ);
        $names = array();
        foreach ($columnNames as $c)
        {
            array_push($names, $c -> Column_name);
        }
        $this -> content[] = $names;

        $count = count($names);

        foreach ($this -> results as $value)
        {
            $values = array();
            $a = 0;
            while ($a < $count)
            {
                array_push($values, $value -> {$names[$a]});
                $a++;
            }
            $this -> content[] = $values;
        }
        return $this;
    }

    public function createCSV()
    {
        $this -> file = fopen($this -> options['directory'] .'/'. $this -> options['name'].'_'.date('Y_m_d').'.csv', 'w');
        foreach ($this -> content as $fields) {
            fputcsv($this -> file, $fields);
        }
        fclose($this -> file);
        return $this;
    }

    public function download()
    {
        $file_url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$this -> options['directory'] .'/'.$this -> options['name'].'_'.date('Y_m_d').'.csv';
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($this -> options['directory'] .'/'.$this -> options['name'].'_'.date('Y_m_d').'.csv');
        die();
    }
}
