<?php
session_start();
require_once("../classes/autoload.php");

switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
        if($_POST["tipo"] == "register") {
            $usuario = new usuario($_POST["usuario"], $_POST["clave"], $_POST["nombre"], $_POST["email"], $_POST["telefono"], $_POST["edad"]);
            $usuario->registrar();
        }
        else if($_POST["tipo"] == "transferencia") 
        {
            $cuenta = new cuenta($_SESSION["usuario"]);

            $obtener_saldo_cuenta_a_enviar = new cuenta($_POST["cuenta"]);

            $cuenta->transferencia($_POST["cuenta"], [ $cuenta->obtener_saldo()-$_POST["cantidad"], $obtener_saldo_cuenta_a_enviar->obtener_saldo() + $_POST["cantidad"] ]);
        }
        else if($_POST["tipo"] == "eliminar") 
        {
            foreach ($_POST["seleccion"] as $usuario_seleccionado) 
            {
                $usuario = new usuario($usuario_seleccionado);
                $usuario->baja_usuario();
            }
        }
        else if($_POST["tipo"] == "modificar") {
            $usuario = new usuario($_POST["usuario"], $_POST["clave"], $_POST["nombre"], $_POST["email"], $_POST["telefono"], $_POST["edad"]);
            $usuario->actualizar_usuario();
        }
        else if($_POST["tipo"] == "dar_de_baja") {
            foreach ($_POST["seleccion"] as $usuario_seleccionado) 
            {
                $cuenta = new cuenta($usuario_seleccionado, $usuario_seleccionado);
                $cuenta->dar_baja();
            }
        }
        else if($_POST["tipo"] == "dar_de_alta") {
            foreach ($_POST["seleccion"] as $usuario_seleccionado) 
            {
                $cuenta = new cuenta($usuario_seleccionado, uniqid());
                $cuenta->dar_alta();
            }
        }
        else if($_POST["tipo"] == "modificar_cuenta") {
            $cuenta = new cuenta($_POST["usuario"], $_POST["numero_cuenta"], $_POST["saldo"]);
            $cuenta->actualizar_cuenta();
        }
        else if($_POST["tipo"] == "insertar_xml") 
        {

            $archivo = "../transferencias.xml"; 

            if (file_exists($archivo)) {
                $xml_archivo = simplexml_load_file($archivo);
                
                $nuevo = $xml_archivo->addChild("transferencia");
                
                $nuevo->addChild("emisor", $_POST["emisor"]);
                $nuevo->addChild("receptor", $_POST["receptor"]);
                $nuevo->addChild("cantidad", $_POST["cantidad"]);
                

                $xml_archivo->asXML($archivo);
            } 
            else 
            {

                $xml_nuevo = new SimpleXMLElement("<transferencias></transferencias>");
                
                $nuevo = $xml_nuevo->addChild("transferencia");

                $nuevo->addChild("emisor", $_POST["emisor"]);
                $nuevo->addChild("receptor", $_POST["receptor"]);
                $nuevo->addChild("cantidad", $_POST["cantidad"]);
                
                $xml_nuevo->asXML($archivo);
            }

        }
        break;
    case "GET":
        if($_GET["tipo"] == "login") {
            $usuario = new usuario($_GET["usuario"], $_GET["clave"]);
            $usuario->iniciar_sesion();
        }
        else if($_GET["tipo"] == "obtener_usuarios") {
            $usuarios = new usuario();
            echo json_encode($usuarios->obtener_usuarios()->fetchAll());
        }
        else if($_GET["tipo"] == "obtener_usuario") {
            $usuario = new usuario($_GET["modificar"]);

            echo json_encode($usuario->obtener_usuario()->fetchAll());
        }
        else if($_GET["tipo"] == "obtener_cuenta") {
            $cuenta = new cuenta($_GET["modificar"]);

            echo json_encode($cuenta->obtener_usuario()->fetchAll());
        }
        else if($_GET["tipo"] == "dados_de_alta") {
            $usuarios = new usuario();
            echo json_encode($usuarios->obtener_usuarios_dados_alta()->fetchAll());
        }
        else if($_GET["tipo"] == "dados_de_baja") {
            $usuarios = new usuario();
            echo json_encode($usuarios->obtener_usuarios_dados_baja()->fetchAll());
        }
        break;
}