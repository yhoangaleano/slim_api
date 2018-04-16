<?php

$base = __DIR__ . '/../src/';

$carpetas = [
    'libs',
    'models/v1',
    'validations',
    'controllers/v1',
    'middlewares',
    'routes'
];

foreach($carpetas as $carpeta){

    foreach(glob($base.$carpeta.'/*.php') as $archivos => $nombreArchivo){

        require $nombreArchivo;

    }

}