<?php

/**
 * Definir una clase de excepción personalizada
 */
class BadRequestException extends Exception
{
    // Redefinir la excepción, por lo que el mensaje no es opcional
    public function __construct($message, $code = 400, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}