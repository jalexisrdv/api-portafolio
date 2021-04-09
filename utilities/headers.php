<?php

require_once 'utilities/JWTWrapper.php';
require_once 'utilities/ResponseWrapper.php';
require_once 'utilities/ResponseCode.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function cors() {
    /**
     * apoyándose de la variable global $_SERVER puede filtrar las URL que no requieran authorization,
     * para hacerlo crea una whitelist y compara las URL con el campo $_SERVER['REQUEST_URI']
     */
    $headers = getallheaders();

    /**
     * cuando se hacen solicitudes verificadas, primero se 
     * intercambian paquetes mediante OPTIONS para verificar
     * que existan los methods solicitados, por esa razon evitamos usar JWT con el metodo OPTIONS
     * */

    if($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
        /*
            Se colocan los subdirectorios de url a excluir.
            Ejemplo de url: https://portafolio.jardvcode.com/api/users/login
            El subdirectorio de la url solicitada es: /api/users/login
        */
        $whiteList = ["/api/users/login"];
        $needAuthorization = false;

        foreach($whiteList as $uri) {
            $needAuthorization = $_SERVER['REQUEST_URI'] !== $uri;
        }
        
        if($needAuthorization) {
            $authorization = str_replace('Bearer ', '', isset($headers['Authorization']) ? $headers['Authorization'] : '');
            $payload = JWTWrapper::decode($authorization);
            if($payload == null) {//TOKEN NO VALIDO
                echo WrapperResponse::createResponse(ResponseCode::UNAUTHORIZED, 'access denied', false);
                die();
            }
            //TOKEN VALIDO ...
            //TOKEN CON EXPIRACIÓN PUEDE APLICAR TODOS LOS VERBOS
            //TOKEN SIN EXPIRACIÓN SOLO PUEDE APLICAR VERBO GET
            if(!isset($payload->exp) && $_SERVER['REQUEST_METHOD'] !== 'GET') {
                echo WrapperResponse::createResponse(ResponseCode::UNAUTHORIZED, 'access denied', false);
                die();
            }
        }
    }

}

//cors();