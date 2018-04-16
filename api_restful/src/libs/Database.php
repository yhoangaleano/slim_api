<?php

namespace Src\Libs;

use FluentPDO, PDO, Exception;

class Database{

    //Singleton

    public $pdo;
    public $fluentpdo;
    static $instancia;

    private function __construct($cadenaConexion){

        try{

            $this->pdo = new PDO($cadenaConexion['dns'], $cadenaConexion['usuario'], $cadenaConexion['contrasenia']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            $this->fluentpdo = new FluentPDO($this->pdo);


        }catch(Exception $e){

            die($e->getMessage());

        }

    }

    private function __clone(){
        
    }

    public static function obtenerInstancia($cadenaConexion){

        //Aplicamos el patrón singleton

        if( !(self::$instancia instanceof self) ){

            self::$instancia = new self($cadenaConexion);

        }

        return self::$instancia;

    }


}