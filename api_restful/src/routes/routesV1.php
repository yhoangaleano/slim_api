<?php

use Src\Controllers\v1\JornaleroController;

$app->group('/v1', function() use ($app) {

    $app->group('/jornaleros', function() use ($app){

        JornaleroController::obtenerInstancia($app);

    });

});