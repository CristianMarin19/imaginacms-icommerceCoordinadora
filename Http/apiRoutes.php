<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => 'icommercecoordinadora'], function (Router $router) {
    
    $router->get('/', [
        'as' => 'icommercecoordinadora.api.coordinadora.init',
        'uses' => 'IcommerceCoordinadoraApiController@init',
    ]);

   

});