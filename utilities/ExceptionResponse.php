<?php

class ExceptionResponse
{

    public static function getMessage($message) {
        $description = preg_split("/:/", $message)[2];
        return trim(preg_replace("/[0-9]+/", "", $description));
    }

}
