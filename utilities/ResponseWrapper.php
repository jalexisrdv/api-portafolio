<?php

class WrapperResponse {

    public static function createResponse($responseCode, $message, $ok, $body = '') {
        http_response_code($responseCode);
        $args = array(
            'message' => $message,
            'ok' => $ok,
        );
        if(!empty($body) || is_null($body)) {
            $args['body'] = $body;
            $response = json_encode($args);
            return $response;
        }
        $response = json_encode($args);
        return $response;
    }

}