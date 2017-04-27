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
     * @param $PDOStatement Builded prepared PDO statement
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
        $this -> names = $arrayNames;
        $this -> content[] = $arrayNames;
    }

    private function setContent()
    {
        foreach ($this -> results as $result)
        {
            $values = array();
            $a = 0;
            while ($a < count($this -> names))
            {
                array_push($values, $result -> {$this->names[$a]});
                $a++;
            }
            $this -> content[] = $values;
        }
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

    public function create()
    {
        $this -> file = fopen($this -> getCSVLocation(), 'w') or die('peut pas ouvrir sa mere');
        foreach ($this -> content as $fields) {
            fputcsv($this -> file, $fields);
        }
        fclose($this -> file);
        return $this;
    }

    public function download()
    {
        $this -> create();
        $file_url = 'http://'.$_SERVER['HTTP_HOST'] .'/'. $this -> getCSVLocation();
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($this -> getCSVLocation());
        die();
    }
}
