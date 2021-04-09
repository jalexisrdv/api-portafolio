<?php

class CategoryConverter {

    public static function fromRequestBody($requestBody) {
        $dto = new CategoryDTO();
        $dto->setId(isset($requestBody->categoryId) ? $requestBody->categoryId : '');
        $dto->setName(isset($requestBody->categoryName) ? $requestBody->categoryName : '');
        return $dto;
    }

}