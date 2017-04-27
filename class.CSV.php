<?php
/**
 * @author TheZdo < https://github.com/newQuery >
 * @link https://github.com/newQuery
 */
class CSV
{

    private $directory = "csv";
    private $name = "default_name";
    /**
     * @param $db PDO Database connection
     * @param $options Array containing key => value for ['table', 'database', 'name', 'directory']
     */
    public function __construct($PDOStatement)
    {

        $PDOStatement -> execute();

        $this -> columnCount = $PDOStatement -> columnCount();
        $this -> count = $PDOStatement -> rowCount();
        if($this -> count > 0)
        {
            $this -> setColumnNames($PDOStatement);
            $this -> results = $PDOStatement -> fetchAll(PDO::FETCH_OBJ);
            $this -> setContent();
            $this -> createCSV();
        }
        else throw new Exception("Error with the PDOStatement", 1);
    }

    public function set($key, $value)
    {
        $this -> {$key} = $value;
        return $this;
    }

    private function setColumnNames($PDOStatement)
    {
        $arrayNames = array();
        foreach(range(0, $PDOStatement->columnCount() - 1) as $column_index)
        {
          array_push($arrayNames,$PDOStatement->getColumnMeta($column_index)['name']);
        }
        $this -> content[] = $arrayNames;
    }

    private function setContent()
    {
        #var_dump($this -> columnCount);
        foreach ($this -> results as $result)
        {
            $values = array();
            $a = 0;
            while ($a <= $this -> count)
            {
                array_push($values, $result);
                $a++;
            }
        }
        $this -> content[] = $values;
        #var_dump($values);
    }

    private function getCSVLocation()
    {
        $txt = '';
        if($this -> directory != '')
        {
            $txt .= $this -> directory .'/'.$this -> name.'_'.date('Y_m_d').'.csv';
        }
        else $txt .= $this -> name.'_'.date('Y_m_d').'.csv';

        return $txt;
    }

    private function createCSV()
    {
        $this -> file = fopen('csv/'. $this -> name.'_'.date('Y_m_d').'.csv', 'w') or die('peut pas ouvrir sa mere');
        foreach ($this -> content as $fields) {
            fputcsv($this -> file, $fields);
        }
        fclose($this -> file);
        return $this;
    }

    public function download()
    {
        $file_url = 'http://'.$_SERVER['HTTP_HOST'] .'/'. $this -> getCSVLocation();
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($this -> getCSVLocation());
        die();
    }
}
