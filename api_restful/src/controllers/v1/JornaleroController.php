<?php

namespace Src\Controllers\v1;

use Slim\Http\Request, 
    Slim\Http\Response,
    Src\Libs\UtilResponse,
    Src\Models\v1\JornaleroModel,
    Src\Validations\v1\JornaleroValidation,
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
        $app->post('/sync', [$this, 'syncJornaleros']);
        
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

        $datos = $request->getParsedBody();
        $this->utilResponse = new UtilResponse();

        $validation = JornaleroValidation::validate( $datos );
        if(!$validation->result){
            return $response->withJson( $validation, 422 );
        }

        try{
            
            $idJornalero = $this->jornaleroModel->createJornalero( $datos );
            $this->utilResponse->setResponse(true, 'El jornalero se inserto correctamente');
            $datos = $this->jornaleroModel->readJornalero( $idJornalero );
            $this->utilResponse->object = $datos;

            return $response->withJson($this->utilResponse, 201 );

        }catch(Exception $e){

            $this->app->logger->error("Jornaleros App - Ruta: POST v1/jornaleros Controlador JornaleroController: createJornalero Error: " . $e->getMessage());

            $this->utilResponse->setResponse(false, "Ocurrio un error inesperado, por favor comuniquese con el administrador del sitio.");
            return $this->response->withJson($this->utilResponse, 500);

        }
    }

    public function readJornaleros(Request $request, Response $response){
        
        $this->utilResponse = new UtilResponse();

        try{
            
            $datos = $this->jornaleroModel->readJornaleros();
            $message = 'Los jornaleros se listaron con éxito';

            if( count( $datos ) == 0 ){
                $message = 'No existen datos para mostrar';
            }

            $this->utilResponse->setResponse(true, $message);
            $this->utilResponse->object = $datos;

            return $response->withJson($this->utilResponse, 200 );

        }catch(Exception $e){

            $this->app->logger->error("Jornaleros App - Ruta: GET v1/jornaleros Controlador JornaleroController: readJornaleros Error: " . $e->getMessage());

            $this->utilResponse->setResponse(false, "Ocurrio un error inesperado, por favor comuniquese con el administrador del sitio.");
            return $this->response->withJson($this->utilResponse, 500);

        }

    }

    public function readJornalero(Request $request, Response $response, array $args){

        $idJornalero = $args['idJornalero'];

        $this->utilResponse = new UtilResponse();

        try{
            
            $datos = $this->jornaleroModel->readJornalero($idJornalero);
            $statusCode = 200;
            $message = 'El jornalero se listo con éxito';

            if( $datos == false ){
                $message = 'No existe el jornalero con ese ID';
                $statusCode = 404;
            }

            $this->utilResponse->setResponse(true, $message);
            $this->utilResponse->object = $datos;

            return $response->withJson($this->utilResponse, $statusCode );

        }catch(Exception $e){

            $this->app->logger->error("Jornaleros App - Ruta: GET v1/jornaleros Controlador JornaleroController: readJornalero Error: " . $e->getMessage());

            $this->utilResponse->setResponse(false, "Ocurrio un error inesperado, por favor comuniquese con el administrador del sitio.");
            return $this->response->withJson($this->utilResponse, 500);

        }

    }

    public function updateJornalero(Request $request, Response $response, array $args){

        $idJornalero = $args['idJornalero'];
        $datos = $request->getParsedBody();
        $datos['idJornalero'] = $idJornalero;

        $this->utilResponse = new UtilResponse();

        $validation = JornaleroValidation::validate( $datos, true );
        if(!$validation->result){
            return $response->withJson( $validation, 422 );
        }

        try{
            
            $actualizo = $this->jornaleroModel->updateJornalero( $datos );

            $statusCode = 200;
            $message = 'El jornalero se actualizo con éxito';

            if( $actualizo != 1 ){
                $message = 'No existe el jornalero con ese ID';
                $statusCode = 404;
            }

            $this->utilResponse->setResponse(true, $message);
            $datos = $this->jornaleroModel->readJornalero( $idJornalero );
            $this->utilResponse->object = $datos;

            return $response->withJson($this->utilResponse, $statusCode );

        }catch(Exception $e){

            $this->app->logger->error("Jornaleros App - Ruta: PUT v1/jornaleros Controlador JornaleroController: updateJornalero Error: " . $e->getMessage());

            $this->utilResponse->setResponse(false, "Ocurrio un error inesperado, por favor comuniquese con el administrador del sitio.");
            return $this->response->withJson($this->utilResponse, 500);

        }

    }

    public function deleteJornalero(Request $request, Response $response, array $args){

        $idJornalero = $args['idJornalero'];

        $this->utilResponse = new UtilResponse();

        try{
            
            $datos = $this->jornaleroModel->deleteJornalero($idJornalero);
            $statusCode = 200;
            $message = 'El jornalero se elimino con éxito';

            if( $datos == false ){
                $message = 'No existe el jornalero con ese ID';
                $statusCode = 404;
            }

            $this->utilResponse->setResponse(true, $message);
            return $response->withJson($this->utilResponse, $statusCode );

        }catch(Exception $e){

            $this->app->logger->error("Jornaleros App - Ruta: DELETE v1/jornaleros Controlador JornaleroController: deleteJornalero Error: " . $e->getMessage());

            $this->utilResponse->setResponse(false, "Ocurrio un error inesperado, por favor comuniquese con el administrador del sitio.");
            return $this->response->withJson($this->utilResponse, 500);

        }

    }

    public function syncJornaleros(Request $request, Response $response){

        $datos = $request->getParsedBody();
        $this->utilResponse = new UtilResponse();
        $datosSync = array();

        try{

            foreach ($datos as $jornalero) {
                $validation = JornaleroValidation::validate( $jornalero );
                if($validation->result == false){
                    return $response->withJson( $validation, 422 );
                }
            }

            foreach ($datos as $jornalero) {
                
                $idJornalero = $this->jornaleroModel->createJornalero( $jornalero );
                array_push( $datosSync, $this->jornaleroModel->readJornalero( $idJornalero ) );

            }

            $this->utilResponse->setResponse( true, 'Los siguientes jornaleros han sido sincronizados' );
            $this->utilResponse->object = $datosSync;
            return $response->withJson( $this->utilResponse, 201 );

        }catch(Exception $e){

            $this->app->logger->error("Jornaleros App - Ruta: POST v1/jornaleros Controlador JornaleroController: syncJornaleros Error: " . $e->getMessage());

            $this->utilResponse->setResponse(false, "Ocurrio un error inesperado, por favor comuniquese con el administrador del sitio.");
            return $this->response->withJson($this->utilResponse, 500);

        }

    }

}