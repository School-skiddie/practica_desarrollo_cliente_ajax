<?php
require_once ("bd.php");

class cuenta extends conexiondb {

    protected $numero_cuenta;
    protected $saldo;
    protected $usuario;

    function __construct($usuario, $numero_cuenta = null, $saldo = 0) 
    {
        $this->numero_cuenta = $numero_cuenta;
        $this->saldo = $saldo; 
        $this->usuario = $usuario;
    }
    
    public function dar_alta()
    {
        parent::__construct();
        $this->insert("cuenta", array(
            ':numero_cuenta' => $this->numero_cuenta, 
            ':saldo' => $this->saldo, 
            ':usuario' => $this->usuario
        ));
    }

    public function actualizar_cuenta() {
        parent::__construct();
        $this->update("cuenta", "usuario=:usuario, numero_cuenta=:numero_cuenta, saldo=:saldo", 
        [ 
            ":usuario" => $this->usuario,
            ":numero_cuenta" => $this->numero_cuenta,
            ":saldo" => $this->saldo
        ]);
    }

    public function transferencia($usuario, $saldos) 
    {
        parent::__construct();
        $this->update("cuenta", "usuario=:usuario, saldo=:saldo", 
        [ 
            ":usuario" => $this->usuario,
            ":saldo" => $saldos[0]
        ]);
        $this->update("cuenta", "usuario=:usuario, saldo=:saldo", 
        [ 
            ":usuario" => $usuario,
            ":saldo" => $saldos[1]
        ]);

        echo "OK";
    }

    public function dar_baja() {
        parent::__construct();
        $this->delete("cuenta", 
        [ 
            ":numero_cuenta" => $this->numero_cuenta
        ]);
    }

    public function datos_cuenta($clave_primaria = null) 
    {
        parent::__construct();
        $datos = $this->select("cuenta", $clave_primaria == null ? $clave_primaria : ["usuario" => $clave_primaria]);

        foreach ($datos as $celda) 
        {
            $this->numero_cuenta = $celda["numero_cuenta"];
            $this->saldo = $celda["saldo"];
            $this->usuario = $celda["usuario"];
        }

        return [ $this->numero_cuenta, $this->saldo, $this->usuario ];
    }

    public function obtener_usuario() {
        parent::__construct();
        return $this->select("cuenta", "WHERE `usuario`='" . $this->usuario . "'");
    }

    public function obtener_saldo() {
        parent::__construct();
        foreach ($this->select("cuenta", "WHERE `usuario`='" . $this->usuario . "'") as $celda) {
            return $celda["saldo"];
        }
    }

}