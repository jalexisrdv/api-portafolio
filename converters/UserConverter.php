<?php

class UserConverter {

    public static function fromRequestBody($requestBody) {
        $dto = new UserDTO();
        $dto->setId(isset($requestBody->id) ? $requestBody->id : '');
        $dto->setUserName(isset($requestBody->username) ? $requestBody->username : '');
        $dto->setPassword(isset($requestBody->password) ? $requestBody->password : '');
        return $dto;
    }

}