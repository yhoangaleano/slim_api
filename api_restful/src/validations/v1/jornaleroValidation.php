<?php

namespace Src\Validations\v1;

use Src\Libs\UtilResponse, DateTime;

class JornaleroValidation
{

    public static function validate($datos, $update = false)
    {

        $utilResponse = new UtilResponse();

        if ($update) {
            $key = 'idJornalero';
            if (empty($datos[$key])) {

                $utilResponse->errors[$key][] = 'El campo ' . $key . ' es obligatorio';

            } else {

                if (!is_numeric($datos[$key])) {

                    $utilResponse->errors[$key][] = 'El campo ' . $key . ' debe ser un número valido';

                }

            }
        }

        $key = 'nombres';
        if (empty($datos[$key])) {

            $utilResponse->errors[$key][] = 'El campo ' . $key . ' es obligatorio';

        } else {

            if (strlen($datos[$key]) < 4) {

                $utilResponse->errors[$key][] = 'El campo ' . $key . ' debe contener 3 caracteres como mínimo';

            } else {

                $pattern = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/';
                if (!preg_match($pattern, $datos[$key])) {
                    $utilResponse->errors[$key][] = 'El campo ' . $key . ' debe contener solo letras y espacios';
                }

            }

        }

        $key = 'fechaNacimiento';
        if (empty($datos[$key])) {

            $utilResponse->errors[$key][] = 'El campo ' . $key . ' es obligatorio';

        } else {

            if (strlen($datos[$key]) < 10) {

                $utilResponse->errors[$key][] = 'El campo ' . $key . ' debe contener 10 caracteres como mínimo';

            } else {

                $formato = 'd/m/Y';

                $fecha = DateTime::createFromFormat($formato, $datos[$key]);

                if (!($fecha && $fecha->format($formato) == $datos[$key])) {
                    $utilResponse->errors[$key][] = 'El campo ' . $key . ' no es una fecha valida o no cumple con el formato dd/mm/aaaa';
                }

            }

        }

        $key = 'correoElectronico';
        if (empty($datos[$key])) {

            $utilResponse->errors[$key][] = 'El campo ' . $key . ' es obligatorio';

        } else {

            if (strlen($datos[$key]) < 4) {

                $utilResponse->errors[$key][] = 'El campo ' . $key . ' debe contener 3 letras como mínimo';

            } else {

                if (!filter_var($datos[$key], FILTER_VALIDATE_EMAIL)) {
                    $utilResponse->errors[$key][] = 'El campo ' . $key . ' debe ser un correo electrónico válido';
                }

            }

        }


        $key = 'peso';
        if (empty($datos[$key])) {

            $utilResponse->errors[$key][] = 'El campo ' . $key . ' es obligatorio';

        } else {

            if (!is_numeric($datos[$key])) {

                $utilResponse->errors[$key][] = 'El campo ' . $key . ' debe ser un número valido';

            }

        }

        $isValid = count($utilResponse->errors) === 0;

        $utilResponse->setResponse($isValid);
        $utilResponse->object = $datos;

        return $utilResponse;

    }
}