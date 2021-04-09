<?php

class UserValidator {

    /**
     * @throws BadRequestException
     */
    public static function create(UserDTO $dto) {
        if(empty($dto->getUserName())) {
            throw new BadRequestException('username es requerido');
        }
        if(empty($dto->getPassword())) {
            throw new BadRequestException('password es requerido');
        }
    }

    /**
     * @throws BadRequestException
     */
    public static function update(UserDTO $dto) {
        if(empty($dto->getId())) {
            throw new BadRequestException('id es requerido');
        }
        $boolean = empty($dto->getUserName()) && empty($dto->getPassword());
        if($boolean) {
            throw new BadRequestException('Campo(s) que desea actualizar requerido(s)');
        }
    }

    /**
     * @throws BadRequestException
     */
    public static function login(UserDTO $dto) {
        return self::create($dto);
    }

}