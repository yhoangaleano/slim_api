<?php

namespace Src\Controllers\v1;

use Slim\Http\Request, 
    Slim\Http\Response,
    Src\Libs\UtilResponse,
    Src\Models\v1\JornaleroModel,
    Exception;

class JornaleroController{

    private $app = null;
    private $jornaleroModel = null;
    private $utilResponse = null;

    static $instancia;

    public function __construct($app){

        $this->app = $app->getContainer();
        $conexiones = $this->app->database;
        $this->jornaleroModel = new JornaleroModel($conexiones);

        $app->post('', [$this, 'createJornalero']);
        $app->get('', [$this, 'readJornaleros']);
        $app->get('/{idJornalero:[0-9]+}', [$this, 'readJornalero']);
        $app->put('/{idJornalero:[0-9]+}', [$this, 'updateJornalero']);
        $app->delete('/{idJornalero:[0-9]+}', [$this, 'deleteJornalero']);
        
    }

    private function __clone(){
        
    }

    public static function obtenerInstancia($app){

        if( !(self::$instancia instanceof self) ){
            self::$instancia = new self($app);
        }

        return self::$instancia;

    }

    public function createJornalero(Request $request, Response $response, array $args){

        $this->utilResponse = new UtilResponse();
        $this->utilResponse->object = $request->getParsedBody();
        $this->utilResponse = $this->utilResponse->setResponse(true, 'El jornalero se inserto correctamente');

        return $response->withJson($this->utilResponse, 201 );

    }

    public function readJornaleros(Request $request, Response $response, array $args){
    }

    public function readJornalero(Request $request, Response $response, array $args){
    }

    public function updateJornalero(Request $request, Response $response, array $args){
    }

    public function deleteJornalero(Request $request, Response $response, array $args){
    }

}