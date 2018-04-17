<?php

$base = __DIR__ . '/../src/';

$carpetas = [
    'libs',
    'models/v1',
    'models/v1',
    'models/v2',    
    'validations/v1',
    'controllers/v1',
    'middlewares',
    'routes'
];

foreach($carpetas as $carpeta){

    foreach(glob($base.$carpeta.'/*.php') as $archivos => $nombreArchivo){

        require $nombreArchivo;

    }

}