<?php

class conexiondb extends config
{
    protected $odb;

    function __construct() 
    {
        try
        {
            $this->odb = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db, $this->user, $this->pass);
        }
        catch(PDOException $e)
        {
            if($this->debug) {
                echo $e->getMessage() . "<br>";
            }
        }
    }

    public function insert($tabla, $valores) {
        try
        {
            $SQL = $this->odb -> prepare("INSERT INTO `" . $tabla . "` VALUES(" . implode(", ", array_keys($valores)) . ")");
            return $SQL -> execute($valores);
        }
        catch(PDOException $e)
        {
            if($this->debug) {
                echo $e->getMessage() . "<br>";
            }
        }
    }

    public function update($tabla, $campos, $valores) {
        try
        {
            $SQL = $this->odb -> prepare("UPDATE `" . $tabla . "` SET " . $campos . " WHERE " . str_replace(":", "" ,array_keys($valores)[0]) . "='" . array_values($valores)[0] . "'");
            return $SQL -> execute($valores);
        }
        catch(PDOException $e)
        {
            if($this->debug) {
                echo $e->getMessage() . "<br>";
            }
        }
    }

    public function delete($tabla, $valores) {
        try 
        {
            $SQL = $this->odb->prepare("DELETE FROM `$tabla` WHERE `" . str_replace(":", "", array_keys($valores)[0]) . "` = ?");
            return $SQL->execute([array_values($valores)[0]]);
        } 
        catch(PDOException $e) {
            if($this->debug) {
                echo $e->getMessage() . "<br>";
            }
        }
    } 

    public function select($tabla, $syntax = "") {
        try
        {
            $SQL = $this->odb -> query("SELECT * FROM `" . $tabla . "` " . $syntax);
            return $SQL;
        }
        catch(PDOException $e)
        {
            if($this->debug) {
                echo $e->getMessage() . "<br>";
            }
        }
    }

    public function select_custom($syntax = "") {
        try
        {
            $SQL = $this->odb -> query($syntax);
            return $SQL;
        }
        catch(PDOException $e)
        {
            if($this->debug) {
                echo $e->getMessage() . "<br>";
            }
        }
    }

    function __destruct() 
    {
        return $this->odb = null;    
    }
}
?>