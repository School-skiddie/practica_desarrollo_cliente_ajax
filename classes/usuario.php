<?php
require_once ("bd.php");

class usuario extends conexiondb {

    protected $usuario;
    protected $clave;
    protected $nombre;
    protected $email;
    protected $telefono;
    protected $edad;

    function __construct($usuario = null, $clave = null, $nombre = null, $email = null, $telefono = null, $edad = null) 
    {
        $this->usuario = $usuario;
        $this->clave = $clave; 
        $this->nombre = $nombre;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->edad = $edad;
    }

    public function iniciar_sesion()
    {
        parent::__construct();

        $existe = $this->select("usuario", "WHERE `usuario`='" . $this->usuario . "' AND `clave`='" . $this->clave . "'");

        if($existe->rowCount() > 0) 
        {
            foreach ($existe as $celda) 
            {
                $this->usuario = $celda["usuario"];
                $this->clave = $celda["clave"]; 
                $this->nombre = $celda["nombre"];
                $this->email = $celda["email"];
                $this->telefono = $celda["telefono"];
                $this->edad = $celda["edad"];
            }

            $_SESSION["usuario"] = $this->usuario;
            $_SESSION["clave"] = $this->clave;
            $_SESSION["nombre"] = $this->nombre;
            $_SESSION["email"] = $this->email;
            $_SESSION["telefono"] = $this->telefono;
            $_SESSION["edad"] = $this->edad;

            echo "OK";

            header("Refresh:3; url=index.php");
        }
        else
        {
            echo "ERROR";
        }
    }

    public function registrar()
    {
        parent::__construct();

        $existe = $this->select("usuario", "WHERE `usuario`='" . $this->usuario . "'");

        if(!($existe->rowCount() > 0)) 
        {
            $this->insert("usuario", array(
                ':usuario' => $this->usuario, 
                ':clave' => $this->clave, 
                ':nombre' => $this->nombre, 
                ':email' => $this->email,
                ':telefono' => $this->telefono,
                ':edad' => $this->edad
            ));

            echo "OK";
        }
        else
        {
            echo "ERROR";
        }
    }

    public function actualizar_usuario() 
    {
        parent::__construct();
        $this->update("usuario", "usuario=:usuario, clave=:clave, nombre=:nombre, email=:email, telefono=:telefono, edad=:edad", 
        [ 
            ":usuario" => $this->usuario,
            ":clave" => $this->clave,
            ":nombre" => $this->nombre,
            ":email" => $this->email,
            ":telefono" => $this->telefono,
            ":edad" => $this->edad
        ]);
    }

    public function baja_usuario() 
    {
        parent::__construct();
        $this->delete("usuario", 
        [ 
            ":usuario" => $this->usuario
        ]);
    }

    public function obtener_usuarios() {
        parent::__construct();
        return $this->select("usuario");
    }

    public function obtener_usuarios_dados_baja() 
    {
        parent::__construct();
        return $this->select_custom("SELECT * 
        FROM `usuario` 
        WHERE usuario.usuario NOT IN (SELECT cuenta.usuario FROM `cuenta`)");
    }

    public function obtener_usuarios_dados_alta() {
        parent::__construct();
        return $this->select_custom("SELECT * FROM `cuenta`");
    }

    public function obtener_usuario() {
        parent::__construct();
        return $this->select("usuario", "WHERE `usuario`='" . $this->usuario . "'");
    }
}