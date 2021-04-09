<?php

class CategoryValidator {

    /**
     * @throws BadRequestException
     */
    public static function create(CategoryDTO $dto) {
        if(empty($dto->getName())) {
            throw new BadRequestException('categoryName es requerido');
        }
    }

    /**
     * @throws BadRequestException
     */
    public static function update(CategoryDTO $dto) {
        if(empty($dto->getId())) {
            throw new BadRequestException('categoryId es requerido');
        }
        $boolean = empty($dto->getName());
        if($boolean) {
            throw new BadRequestException('Campo(s) que desea actualizar requerido(s)');
        }
    }

}