<?php

namespace Src\Libs;

class UtilResponse{

    // Esta variable va contener todos los datos ya sean: un objeto, una lista, un numero que nosotros le queramos responder al cliente
    public $object = null;

    // Va a responder con true o false para identificar si la petición se hizo correctamente o si ocurrio algún error 
    public $result = false;

    // Vamos a responder mensajes apropiados para cada error o cada situación
    public $message = '';

    // Array que contiene todos los errores que sucedieron durante la petición.
    public $errors = [];


    public function setResponse($result, $message = ''){
        
        $this->result = $result;
        $this->message = $message;

        if($result == false && $message == ''){
            $this->message = 'Ocurrió un error inesperado, por favor verifique los datos enviados';
        }

        return $this;

    }

}